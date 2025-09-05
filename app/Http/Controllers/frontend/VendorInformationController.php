<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;

use App\Models\VendorStaffAssociation;
use App\Models\VendorServiceAssociation;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\Category;
use App\Models\Vendor;


class VendorInformationController extends Controller
{
    public function view()
    {
        // Check if user is logged in
        if (Auth::check()) {
            $activeVendor = Vendor::where('status', config('constants.status.active'))->get();

            $currentUserId = Auth::user()->id;
            $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');
            if ($vendorId) {
                $userIds = VendorStaffAssociation::where('vendor_id', $vendorId)
                    ->where('user_id', '!=', $currentUserId)
                    ->pluck('user_id')
                    ->toArray();

                $staffdata = User::whereIn('id', $userIds)->get();

                // Get vendor services
                $serviceIds = VendorServiceAssociation::with('vendor')
                    ->where('vendor_id', $vendorId)
                    ->pluck('service_id');
                $servicedata = Service::whereIn('id', $serviceIds)->get();
                $categories = Category::select('id', 'category_name')->get();

                $bookingdata = Booking::where('vendor_id', $vendorId)->get();
                $bookingTemplateIds = $bookingdata->pluck('booking_template_id')->unique();

                $bookingtemplatedata = collect();
                if ($bookingTemplateIds->isNotEmpty()) {
                    $bookingtemplatedata = BookingTemplate::whereIn('id', $bookingTemplateIds)->get();
                }
                $currencies = config('constants.currencies');
                return view(
                    'frontend.vendor.view',
                    compact('staffdata', 'servicedata', 'categories', 'currencies', 'bookingdata', 'bookingtemplatedata', 'activeVendor')
                );
            }
        }
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
    /*========================== profile update ====================*/
    public function UserprofileUpdate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg|max:2048',
        ]);
        $user = auth()->user();
        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->filled('remove_avatar') && $request->remove_avatar == '1') {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = null;
        }
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'avatar' => $avatarPath,
        ]);
        return redirect()->route('vendor.view')->with('success', 'Profile updated successfully.');
    }
    /*========================== Booking Tab ====================*/

    /*==========================  Bookings  ==========================*/
    public function bookingdestroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('vendor.view')->with('success', 'Booking deleted successfully.');
    }
    /*========================== Service Tab ====================*/

    public function ServiceCreate(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|integer|exists:categories,id',
            'price'       => 'nullable|numeric|min:0',
            'currency'    => 'required|string|max:5',
            'duration'    => 'nullable|integer|min:1',
            'status'      => 'required|boolean',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Create service without thumbnail and gallery
        $service = Service::create([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'price'       => $request->price,
            'currency'    => $request->currency ?? '₹',
            'duration'    => $request->duration,
            'status'      => $request->status,
            'user_id'     => auth()->id(),
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('services/thumbnails', 'public');
            $service->thumbnail = $path;
        }

        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            $galleryImages = [];
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('services/gallery', 'public');
                $galleryImages[] =  $path;
            }
            $service->gallery = json_encode($galleryImages);
        }

        $service->save();

        // Associate service with vendor
        $vendorId = VendorStaffAssociation::where('user_id', auth()->id())->value('vendor_id');
        if ($vendorId) {
            VendorServiceAssociation::create([
                'vendor_id'  => $vendorId,
                'service_id' => $service->id,
            ]);
        }

        return back()->with('success', 'Service added successfully.');
    }

    public function ServiceUpdate(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|integer|exists:categories,id',
            'currency'    => 'required|string|max:5',
            'price'       => 'nullable|numeric|min:0',
            'duration'    => 'required|integer|min:1',
            'status'      => 'required|boolean',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Update basic fields
        $service->name        = $request->name;
        $service->description = $request->description;
        $service->category    = $request->category;
        $service->currency    = $request->currency;
        $service->price       = $request->price;
        $service->duration    = $request->duration;
        $service->status      = $request->status;

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($service->thumbnail && file_exists(public_path('storage/' . $service->thumbnail))) {
                unlink(public_path('storage/' . $service->thumbnail));
            }
            $service->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        } elseif ($request->input('remove_thumbnail')) {
            // Remove thumbnail if cut
            if ($service->thumbnail && file_exists(public_path('storage/' . $service->thumbnail))) {
                unlink(public_path('storage/' . $service->thumbnail));
            }
            $service->thumbnail = null;
        }

        // Handle gallery
        $gallery = [];

        // Existing gallery from hidden inputs
        $existingGallery = $request->input('existing_gallery', []);
        $deletedGallery  = $request->input('delete_gallery', []);

        // Delete removed gallery images
        foreach ($deletedGallery as $file) {
            if (file_exists(public_path($file))) {
                unlink(public_path($file));
            }
        }

        // Keep remaining existing images
        $gallery = array_diff($existingGallery, $deletedGallery);

        // Add newly uploaded gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('gallery', 'public');
            }
        }

        $service->gallery = json_encode(array_values($gallery));

        $service->save();

        return redirect()->back()->with('success', 'Service updated successfully.');
    }


    public function Servicedestroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('vendor.view')->with('success', 'Service deleted successfully.');
    }

    /*========================== Staff Tab ====================*/
}
