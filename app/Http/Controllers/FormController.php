<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingTemplate;
use App\Models\Booking;
use App\Helpers\FormHelper;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function show($id)
    {
        $template = BookingTemplate::findOrFail($id);
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

        Booking::create([
            'booking_template_id' => $template->id,
            'customer_id' => auth()->id() ?? null, 
            'booking_datetime' => $request->input('booking_datetime', now()), 
            'selected_staff' => $request->input('selected_staff', 'Customer User'),
            'booking_data' => json_encode($bookingData),
        ]);

        return redirect()
            ->route('form.show', $template->id)
            ->with('success', 'Form submitted successfully!');
    }
}
