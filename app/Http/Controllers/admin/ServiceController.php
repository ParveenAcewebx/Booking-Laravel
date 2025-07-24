<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingTemplate;
use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Helpers\FormHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\StaffServiceAssociation;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        if ($request->ajax()) {
            $services = Service::with('staffAssociations')->get();

            return DataTables::of($services)
                ->addColumn('status', function ($row) {
                    $statuses = config('constants.status');

                    return $row->status == $statuses['active']
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('description', function ($row) {
                    return $row->description;
                })
                ->addColumn('staff_member', function ($row) {
                    // Get staff IDs from staff_associations
                    $staffIds = $row->staffAssociations->pluck('staff_member')->toArray();
                    // Fetch user names
                    $staffNames = User::whereIn('id', $staffIds)->pluck('name')->toArray();
                    return implode(', ', $staffNames);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('edit services')) {
                        $btn .= '<a href="' . route('service.edit', $row->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Service">
                            <i class="fas fa-pencil-alt"></i>
                        </a> ';
                    }

                    if (auth()->user()->can('delete services')) {
                        $btn .= '<form id="delete-service-' . $row->id . '" action="' . route('service.delete', $row->id) . '" method="POST" style="display:inline;">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="button" class="btn btn-icon btn-danger" onclick="deleteService(' . $row->id . ', event)" title="Delete">';
                        $btn .= '<i class="feather icon-trash-2"></i>';
                        $btn .= '</button>';
                        $btn .= '</form>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'description'])
                ->make(true);
        }
        return view('admin.service.index', compact('loginUser'));
    }

    public function serviceAdd(Request $request)
    {
        $categories = Category::where('status', config('constants.status.active'))->get();
        $staffUsers = User::role('staff')->get();
        $defaultStatus = config('constants.status');
        $currencies = config('constants.currencies');
        $appointmentStats = config('constants.appointment_status');
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.service.add', compact('categories', 'staffUsers', 'defaultStatus', 'currencies', 'appointmentStats', 'loginUser'));
    }

    public function servicestore(Request $request)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'category'               => 'nullable|exists:categories,id',
            'thumbnail'              => 'nullable|file|mimes:jpg,jpeg,gif,png,webp|max:2048',
            'gallery.*'              => 'nullable|file|mimes:jpg,jpeg,gif,png,webp|max:2048',
            'status'                 => 'required|in:0,1',
            'price'                  => 'nullable|numeric',
            'currency'               => 'nullable|string|max:5',
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

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $gallery = [];

        if ($request->has('existing_gallery')) {
            $existingGallery = $request->existing_gallery;
            $deletedGallery = $request->delete_gallery ?? [];

            foreach ($deletedGallery as $file) {
                Storage::disk('public')->delete($file);
            }

            $gallery = array_diff($existingGallery, $deletedGallery);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('galleries', 'public');
            }
        }

        $data['gallery'] = json_encode(array_values($gallery));

        $data['staff_member'] = json_encode($request->input('staff_member', []));
        $data['payment__is_live'] = $request->has('payment__is_live') ? 1 : 0;
        $data['duration'] = $request->duration;

        $service = Service::create($data);
        $lastServiceId = $service->id;
        $staffMembers = $request->input('staff_member', []);
        foreach ($staffMembers as $staffId) {
            StaffServiceAssociation::create([
                'service_id' => $lastServiceId,
                'staff_member' => $staffId
            ]);
        }
        return redirect()->route('service.list')->with('success', 'Service Created Successfully');
    }

    public function destroy(Service $service)
    {
        StaffServiceAssociation::where('service_id', $service->id)->delete();
        $service->delete();
        return response()->json(['success' => true]);
    }

    public function serviceEdit(Service $service)
    {
        $categories = Category::where('status', config('constants.status.active'))->get();
        $staffUsers = User::role('staff')->get();
        $currencies = config('constants.currencies');
        $appointmentStats = config('constants.appointment_status');
        $statuses = config('constants.status');
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        $associatedStaffIds = $service->staffAssociations()->pluck('staff_member')->toArray();

        return view('admin.service.edit', compact(
            'service',
            'categories',
            'staffUsers',
            'currencies',
            'appointmentStats',
            'statuses',
            'loginUser',
            'associatedStaffIds'
        ));
    }

    public function serviceUpdate(Request $request, $id)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'category'              => 'nullable|exists:categories,id',
            'thumbnail'             => 'nullable|file|mimes:jpg,jpeg,gif,png,webp|max:2048',
            'gallery.*'             => 'nullable|file|mimes:jpg,jpeg,gif,png,webp|max:2048',
            'existing_gallery'      => 'nullable|array',
            'existing_gallery.*'    => 'string',
            'delete_gallery'        => 'nullable|array',
            'delete_gallery.*'      => 'string',
            'staff_member'          => 'nullable|array',
            'staff_member.*'        => 'exists:users,id',
            'status'                => 'required|in:0,1',
            'price'                 => 'nullable|numeric|min:0',
            'currency'              => 'nullable|string|max:5',
            'appointment_status'    => 'nullable|in:0,1',
            'cancelling_unit'       => 'required|in:hours,days',
            'redirect_url'          => 'nullable|url',
            'payment_mode'          => 'nullable|in:on_site,stripe',
            'payment_account'       => 'nullable|in:default,custom',
            'stripe_test_site_key'  => 'nullable|string',
            'stripe_test_secret_key' => 'nullable|string',
            'stripe_live_site_key'  => 'nullable|string',
            'stripe_live_secret_key' => 'nullable|string',
            'payment__is_live'      => 'nullable|boolean',
            'remove_thumbnail'      => 'nullable|in:0,1',
        ]);

        $service = Service::findOrFail($id);

        // Fill base fields
        $service->fill([
            'name'                  => $request->name,
            'description'           => $request->description,
            'category'              => $request->category,
            'duration'              => $request->duration,
            'status'                => $request->status,
            'price'                 => $request->price,
            'currency'              => $request->currency,
            'appointment_status'    => $request->appointment_status,
            'cancelling_unit'       => $request->cancelling_unit,
            'cancelling_value'      => $request->cancelling_value,
            'redirect_url'          => $request->redirect_url,
            'payment_mode'          => $request->payment_mode,
            'payment_account'       => $request->payment_account,
            'payment__is_live'      => $request->has('payment__is_live') ? 1 : 0,
        ]);

        $service->stripe_test_site_key   = $request->stripe_test_site_key;
        $service->stripe_test_secret_key = $request->stripe_test_secret_key;
        $service->stripe_live_site_key   = $request->stripe_live_site_key;
        $service->stripe_live_secret_key = $request->stripe_live_secret_key;

        if ($request->hasFile('thumbnail')) {
            // Remove old one if exists
            if ($service->thumbnail && Storage::disk('public')->exists($service->thumbnail)) {
                Storage::disk('public')->delete($service->thumbnail);
            }
            // Store new
            $service->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        } elseif ($request->input('remove_thumbnail') == '1') {
            // If removal flag is set and no new image
            if ($service->thumbnail && Storage::disk('public')->exists($service->thumbnail)) {
                Storage::disk('public')->delete($service->thumbnail);
            }
            $service->thumbnail = null;
        }

        // Process gallery deletions
        $existingGallery = $request->input('existing_gallery', []);
        $deletedGallery = $request->input('delete_gallery', []);

        foreach ($deletedGallery as $deletedPath) {
            Storage::disk('public')->delete($deletedPath);
        }

        $finalGallery = array_diff($existingGallery, $deletedGallery);

        // Upload new images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $finalGallery[] = $file->store('gallery', 'public');
            }
        }
        $service->gallery = json_encode(array_values($finalGallery));
        $service->save();

        // Handle staff associations
        $newStaffIds = $request->input('staff_member', []);
        $existingStaffIds = StaffServiceAssociation::where('service_id', $service->id)
            ->pluck('staff_member')
            ->toArray();

        $toAdd = array_diff($newStaffIds, $existingStaffIds);
        foreach ($toAdd as $staffId) {
            StaffServiceAssociation::create([
                'service_id'   => $service->id,
                'staff_member' => $staffId,
            ]);
        }

        $toDelete = array_diff($existingStaffIds, $newStaffIds);
        if (!empty($toDelete)) {
            StaffServiceAssociation::where('service_id', $service->id)
                ->whereIn('staff_member', $toDelete)
                ->delete();
        }
        return redirect()->route('service.list')->with('success', 'Service Updated Successfully!');
    }
}
