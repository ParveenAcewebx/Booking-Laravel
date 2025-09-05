<?php

namespace App\Http\Controllers\Frontend\Vendor;

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
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffServiceAssociation;

class VendorServiceController extends Controller
{
 public function view(){
       // Check if user is logged in
    if (Auth::check()) {
        $currentUserId = Auth::user()->id;
        // Get vendor ID of logged-in user
        $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');

        if ($vendorId) {
            // Get all staff (excluding current user) under the same vendor
            $userIds = VendorStaffAssociation::where('vendor_id', $vendorId)
                        ->where('user_id', '!=', $currentUserId)
                        ->pluck('user_id')
                        ->toArray();

            $staffdata = User::whereIn('id', $userIds)
                        ->orderBy('id', 'desc')
                        ->get();

            $staffid = $staffdata->pluck('id');
           
            if ($staffid) {
                $staffServices = StaffServiceAssociation::whereIn('staff_member', $staffid)->with('service:id,name')->get()->groupBy('staff_member')->map(function ($items) {
                    return $items->map(function ($item) {
                        return [
                            'id'   => optional($item->service)->id,
                            'name' => optional($item->service)->name,
                        ];
                    })->filter(function ($service) {
                        return !is_null($service['id']);
                    })->toArray();
                });

                    $StaffworkdaysDayoff = Staff::whereIn('user_id', $staffid)->get()->map(function ($staff) {
                        return [
                            'user_id'   => $staff->user_id,
                            'work_days' => json_decode($staff->work_hours, true), 
                            'days_off'  => json_decode($staff->days_off, true),   
                        ];
                    });
            }

            // Get vendor services
            $serviceIds = VendorServiceAssociation::with('vendor')
                        ->where('vendor_id', $vendorId)
                        ->pluck('service_id');

            $servicedata = Service::whereIn('id', $serviceIds)->get();

            // Categories
            $categories = Category::select('id', 'category_name')->get();

            // Bookings
            $bookingdata = Booking::where('vendor_id', $vendorId)->get();
            $bookingTemplateIds = $bookingdata->pluck('booking_template_id')->unique();

            $bookingtemplatedata = collect();
            if ($bookingTemplateIds->isNotEmpty()) {
                $bookingtemplatedata = BookingTemplate::whereIn('id', $bookingTemplateIds)->get();
            }

            // Constants
            $currencies     = config('constants.currencies');
            $weekDays       = config('constants.week_days');
            $phoneCountries = config('phone_countries');

            return view('frontend.vendor.tabs.services.service', compact(
                'staffdata',
                'StaffworkdaysDayoff',
                'staffServices',
                'weekDays',
                'phoneCountries',
                'servicedata',
                'categories',
                'currencies',
                'bookingdata',
                'bookingtemplatedata'
            ));
        }

     }
}
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
    return redirect()->route('vendor.services.view')->with('success', 'Service added successfully.');
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
        if ($service->thumbnail && file_exists(public_path('storage/'.$service->thumbnail))) {
            unlink(public_path('storage/'.$service->thumbnail));
        }
        $service->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
    } elseif ($request->input('remove_thumbnail')) {
        if ($service->thumbnail && file_exists(public_path('storage/'.$service->thumbnail))) {
            unlink(public_path('storage/'.$service->thumbnail));
        }
        $service->thumbnail = null;
    }
    // Handle gallery
    $gallery = [];
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
    if ($request->hasFile('gallery')) {
        foreach ($request->file('gallery') as $image) {
            $gallery[] = $image->store('gallery', 'public');
        }
    }
    $service->gallery = json_encode(array_values($gallery));
    $service->save();
    return redirect()->route('vendor.services.view')->with('success', 'Service updated successfully.');
}

   public function Servicedestroy($id){
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('vendor.services.view')->with('success', 'Service deleted successfully.');
    }
}
