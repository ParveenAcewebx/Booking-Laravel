<?php

namespace App\Http\Controllers;

use App\Helpers\Shortcode;

use Illuminate\Http\Request;
use App\Models\BookingTemplate;
use App\Models\Booking;
use App\Models\User;
use App\Helpers\FormHelper;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function show($slug)
    {
        $template = BookingTemplate::where('slug', $slug)->firstOrFail();
        $formHtml = FormHelper::renderDynamicFieldHTML($template->data);
        return view('form.show', compact('formHtml', 'template'));
    }

    public function store(Request $request, $id)
    {
        $template = BookingTemplate::findOrFail($id);
        $bookingData = json_decode($request->input('booking_data'), true) ?? [];
        $inputData = $request->input('dynamic', []);
        $files = $request->file('dynamic', []);
        foreach ($inputData as $key => $val) {
            $bookingData[$key] = $val;
        }

        if (is_array($files)) {
            foreach ($files as $key => $fileInput) {
                if (is_array($fileInput)) {
                    $paths = [];
                    foreach ($fileInput as $file) {
                        if ($file && $file->isValid()) {
                            $paths[] = $file->store('bookings', 'public');
                        }
                    }
                    $bookingData[$key] = $paths;
                } elseif ($fileInput && $fileInput->isValid()) {
                    $bookingData[$key] = $fileInput->store('bookings', 'public');
                }
            }
        }
        $user = User::create([
            'name' => $bookingData['first_name'] . ' ' . $bookingData['last_name'],
            'email' => $bookingData['email'],
            'phone_number' => $bookingData['phone'],
            'password' => bcrypt($request->password),
            'avatar' => '',
            'status' => $request->has('status') ? config('constants.status.active') : config('constants.status.inactive'),
        ]);
        $user->assignRole('customer');
        $lastInsertedId = $user->id;
        Booking::create([
            'booking_template_id'       => $template->id,
            'customer_id'               => auth()->id() ?? $lastInsertedId,
            'booking_datetime'          => $request->input('booking_datetime', now()),
            'selected_staff'            => $request->input('selected_staff', 'NULL'),
            'first_name'                => $bookingData['first_name'],
            'last_name'                 => $bookingData['last_name'],
            'phone_number'              => $bookingData['phone'],
            'email'                     => $bookingData['email'],
            'booking_data'              => json_encode($bookingData),

        ]);

        return redirect()
            ->route('form.show', $template->id)
            ->with('success', 'Form submitted successfully!');
    }
}
