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
    public $vendorId;
    public $vendorServices;
    public $vendorServiceNames;
    public $phoneCountries;
    public $weekDays;
    public $currencies;
    public $CurrentUserId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $currentUserId = Auth::id();
            $this->CurrentUserId = $currentUserId;
            $this->vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');
            if ($this->vendorId) {
                $this->vendorServices = VendorServiceAssociation::where('vendor_id', $this->vendorId)->pluck('service_id');
                $this->vendorServiceNames = Service::whereIn('id', $this->vendorServices)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                        ];
                    });
            } else {
                $this->vendorServices = collect();
                $this->vendorServiceNames = collect();
            }

            $this->currencies     = config('constants.currencies');
            $this->weekDays       = config('constants.week_days');
            $this->phoneCountries = config('phone_countries');
            return $next($request);
        });
    }
    /*=============================== service list =========================*/

    public function view()
    {
        // Check if user is logged in
        if (Auth::check()) {
            $currentUserId = Auth::user()->id;
            $vendorId = $this->vendorId;
            if ($vendorId) {
                $userIds = VendorStaffAssociation::where('vendor_id', $vendorId)
                    ->where('user_id', '!=', $this->CurrentUserId)
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
                $servicedata = Service::whereIn('id', $serviceIds)->orderBy('id', 'desc')->paginate(3);
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
                $currencies     = $this->currencies;
                $weekDays       = $this->weekDays;
                $phoneCountries = $this->phoneCountries;

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
    /*=============================== service add =========================*/
    public function add()
    {
        $categories = Category::select('id', 'category_name')->get();
        $currencies     = $this->currencies;
        $servicedata = Service::where('id')->get();
        return view('frontend.vendor.tabs.services.add', compact(
            'categories',
            'currencies',
        ));
    }

    /*=============================== service edit =========================*/
    public function edit($id)
    {
        $serviceIds = $id;
        $categories = Category::select('id', 'category_name')->get();
        $currencies     = $this->currencies;
        $servicedata = Service::where('id', $serviceIds)->get();
        return view('frontend.vendor.tabs.services.edit', compact(
            'categories',
            'currencies',
            'servicedata',
        ));
    }

    /*=============================== service create =========================*/
    public function ServiceCreate(Request $request)
    {
        $request->validate([
            'name'                    => 'required|string|max:255',
            'description'             => 'nullable|string',
            'category'                => 'nullable|integer|exists:categories,id',
            'price'                   => 'nullable|numeric|min:0',
            'currency'                => 'required|string|max:5',
            'duration'                => 'nullable|integer|min:1',
            'status'                  => 'required|boolean',
            'thumbnail'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'appointment_status'      => 'nullable|in:0,1',
            'cancelling_unit'         => 'required|in:hours,days',
            'cancelling_value'        => 'required|integer',
            'redirect_url'            => 'nullable|url',
            'payment_mode'            => 'nullable|in:on_site,stripe',
            'payment_account'         => 'nullable|in:default,custom',
            'stripe_test_site_key'    => 'nullable|string',
            'stripe_test_secret_key'  => 'nullable|string',
            'stripe_live_site_key'    => 'nullable|string',
            'stripe_live_secret_key'  => 'nullable|string',
            'payment__is_live'        => 'nullable|boolean',
        ]);

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('services/thumbnails', 'public');
        }

        // Handle gallery upload
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('services/gallery', 'public');
            }
        }

        // Create service
        $service = Service::create([
            'name'                    => $request->name,
            'description'             => $request->description ?? '',
            'category'                => $request->category,
            'price'                   => $request->price ?? 0.00,
            'currency'                => $request->currency ?? '₹',
            'duration'                => $request->duration ?? 30,
            'status'                  => $request->status,
            'appointment_status'      => $request->appointment_status ?? 0,
            'cancelling_unit'         => $request->cancelling_unit ?? 'hours',
            'cancelling_value'        => $request->cancelling_value ?? 1,
            'redirect_url'            => $request->redirect_url ?? '',
            'payment_mode'            => $request->payment_mode ?? 'on_site',
            'payment_account'         => $request->payment_account ?? 'default',
            'stripe_test_site_key'    => $request->stripe_test_site_key ?? '',
            'stripe_test_secret_key'  => $request->stripe_test_secret_key ?? '',
            'stripe_live_site_key'    => $request->stripe_live_site_key ?? '',
            'stripe_live_secret_key'  => $request->stripe_live_secret_key ?? '',
            'payment__is_live'        => $request->payment__is_live ?? 0,
            'thumbnail'               => $thumbnailPath,
            'gallery'                 => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'user_id'                 => auth()->id(),
        ]);

        // Associate service with vendor
        $vendorId = VendorStaffAssociation::where('user_id', auth()->id())->value('vendor_id');
        if ($vendorId) {
            VendorServiceAssociation::create([
                'vendor_id'  => $vendorId,
                'service_id' => $service->id,
            ]);
        }

        return redirect()->route('vendor.services.view')->with('success', 'Service Added Successfully.');
    }


    public function ServiceUpdate(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'category'               => 'nullable|integer|exists:categories,id',
            'currency'               => 'required|string|max:5',
            'price'                  => 'nullable|numeric|min:0',
            'duration'               => 'required|integer|min:1',
            'status'                 => 'required|boolean',
            'thumbnail'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'appointment_status'     => 'nullable|in:0,1',
            'cancelling_unit'        => 'required|in:hours,days',
            'cancelling_value'       => 'required|integer',
            'redirect_url'           => 'nullable|url',
            'payment_mode'           => 'nullable|in:on_site,stripe',
            'payment_account'        => 'nullable|in:default,custom',
            'stripe_test_site_key'   => 'nullable|string',
            'stripe_test_secret_key' => 'nullable|string',
            'stripe_live_site_key'   => 'nullable|string',
            'stripe_live_secret_key' => 'nullable|string',
            'payment__is_live'       => 'nullable|boolean',
        ]);

        // Update basic fields
        $service->fill([
            'name'                   => $request->name,
            'description'            => $request->description ?? '',
            'category'               => $request->category,
            'currency'               => $request->currency,
            'price'                  => $request->price ?? 0.00,
            'duration'               => $request->duration ?? 30,
            'status'                 => $request->status,
            'appointment_status'     => $request->appointment_status ?? 0,
            'cancelling_unit'        => $request->cancelling_unit ?? 'hours',
            'cancelling_value'       => $request->cancelling_value ?? 1,
            'redirect_url'           => $request->redirect_url ?? '',
            'payment_mode'           => $request->payment_mode ?? 'on_site',
            'payment_account'        => $request->payment_account ?? 'default',
            'stripe_test_site_key'   => $request->filled('stripe_test_site_key') ? $request->stripe_test_site_key : null,
            'stripe_test_secret_key' => $request->filled('stripe_test_secret_key') ? $request->stripe_test_secret_key : null,
            'stripe_live_site_key'   => $request->filled('stripe_live_site_key') ? $request->stripe_live_site_key : null,
            'stripe_live_secret_key' => $request->filled('stripe_live_secret_key') ? $request->stripe_live_secret_key : null,
            'payment__is_live'       => $request->has('payment__is_live') ? (bool)$request->payment__is_live : false,
        ]);

        // === Handle thumbnail ===
        $removeExisting = $request->input('remove_thumbnail') == 1;

        if ($request->hasFile('thumbnail')) {
            if ($service->thumbnail && file_exists(storage_path('app/public/' . $service->thumbnail))) {
                unlink(storage_path('app/public/' . $service->thumbnail));
            }
            $service->thumbnail = $request->file('thumbnail')->store('services/thumbnails', 'public');
        } elseif ($removeExisting) {
            if ($service->thumbnail && file_exists(storage_path('app/public/' . $service->thumbnail))) {
                unlink(storage_path('app/public/' . $service->thumbnail));
            }
            $service->thumbnail = null;
        }
        // Else: keep existing thumbnail

        // === Handle gallery ===
        $gallery = [];
        $existingGallery = $request->input('existing_gallery', []);
        $deletedGallery  = $request->input('delete_gallery', []);

        // Delete removed gallery images
        foreach ($deletedGallery as $file) {
            if (file_exists(storage_path('app/public/' . $file))) {
                unlink(storage_path('app/public/' . $file));
            }
        }

        // Keep remaining existing images
        $gallery = array_diff($existingGallery, $deletedGallery);

        // Add new gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('services/gallery', 'public');
            }
        }

        $service->gallery = json_encode(array_values($gallery));
        $service->save();

        return redirect()->route('vendor.services.view')->with('success', 'Service Updated Successfully.');
    }



    public function Servicedestroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('vendor.services.view')->with('success', 'Service Deleted Successfully.');
    }
}
