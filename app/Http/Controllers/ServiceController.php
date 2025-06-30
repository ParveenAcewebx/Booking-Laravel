<?php

namespace App\Http\Controllers;

use App\Helpers\Shortcode;
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

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::all();

            return DataTables::of($services)
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Inactive</span>';
                    }
                })
                ->addColumn('description', function ($row) {
                    return strip_tags($row->description);
                })
                ->addColumn('staff_member', function ($row) {
                    $staffIds = json_decode($row->staff_member, true) ?? [];
                    $staff = User::whereIn('id', $staffIds)->pluck('name')->toArray();
                    return implode(', ', $staff);
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
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('service.index');
    }

    public function serviceAdd(Request $request)
    {
        $categories = Category::where('status', config('constants.status.active'))->get();
        $staffUsers = User::role('staff')->get();
        $defaultStatus = config('constants.status');
        $currencies = config('constants.currencies');
        $appointmentStats = config('constants.appointment_status');
        return view('service.add', compact('categories', 'staffUsers', 'defaultStatus', 'currencies', 'appointmentStats'));
    }

    public function servicestore(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('gallery')) {
            $paths = [];
            foreach ($request->file('gallery') as $file) {
                $paths[] = $file->store('galleries', 'public');
            }
            $data['gallery'] = json_encode($paths);
        }
        $data['staff_member'] = json_encode($request->input('staff_member', []));
        $data['payment__is_live'] = $request->has('payment__is_live') ? 1 : 0;
        $data['description'] = $request->input('description');
        $data['category'] = $request->input('category');
        $data['price'] = $request->input('price');
        $data['currency'] = $request->input('currency');
        $data['cancelling_unit'] = $request->input('cancelling_unit');
        $data['cancelling_value'] = $request->input('cancelling_value');
        $data['stripe_test_site_key'] = $request->input('stripe_test_site_key');
        $data['stripe_test_secret_key'] = $request->input('stripe_test_secret_key');
        $data['stripe_live_secret_key'] = $request->input('stripe_live_secret_key');
        $data['stripe_live_site_key'] = $request->input('stripe_live_site_key');
        $data['duration'] = $request->input('duration');
        $data['payment_mode'] = $request->input('payment_mode');
        $data['payment_account'] = $request->input('payment_account');
        $data['redirect_url'] = $request->input('redirect_url');

        Service::create($data);
        return redirect()
            ->route('service.list')
            ->with('success', 'Service created successfully');
    }

    public function serviceDelete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(['success' => true]);
    }

    public function serviceEdit($id)
    {
        $service = Service::findOrFail($id);
        $categories = Category::where('status', config('constants.status.active'))->get();
        $staffUsers = User::role('staff')->get();
        $currencies = config('constants.currencies');
        $appointmentStats = config('constants.appointment_status');
        $statuses = config('constants.status');

        return view('service.edit', compact(
            'service',
            'categories',
            'staffUsers',
            'currencies',
            'appointmentStats',
            'statuses'
        ));
    }


    public function serviceUpdate(Request $request)
    {
        return view('service.index');
    }
}
