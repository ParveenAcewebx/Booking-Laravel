<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\BookingTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class BookingTemplateController extends Controller
{
    protected $allUsers;

    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index(Request $request)
    {
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        if ($request->ajax()) {
            $query = BookingTemplate::select(['id', 'template_name', 'created_at', 'created_by', 'slug', 'status', 'data'])
                ->with('user')
                ->withCount('bookings');

            return DataTables::of($query)
                ->addColumn('checkbox', function ($row) {
                    if ($row->bookings_count == 0) {
                        return '<input type="checkbox" class="selectRow" value="' . $row->id . '">';
                    }
                    return '<input type="checkbox" class="selectRow" value="' . $row->id . '" disabled title="Cannot delete: bookings exist">';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format(get_setting('date_format', 'Y-m-d') . ' ' . get_setting('time_format', 'H:i'))
                        : '';
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge ' . ($row->status === 1 ? 'badge-success' : 'badge-danger') . '">'
                        . ($row->status === 1 ? "Active" : "Inactive") . '</span>';
                })
                ->addColumn('created_by', function ($row) {
                    return $row->user->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (Auth::user()->can('edit templates')) {
                        $btn .= '<a href="' . route('template.edit', [$row->id]) . '" class="btn btn-icon btn-success" title="Edit Form">
                                    <i class="fas fa-pencil-alt"></i>
                                </a> ';
                    }

                    if (Auth::user()->can('delete templates')) {
                        if ($row->bookings_count == 0) {
                            $btn .= '<form action="' . route('template.delete', [$row->id]) . '" method="POST" id="deleteTemplate-' . $row->id . '" style="display:inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        ' . csrf_field() . '
                                        <button type="button" onclick="return deleteTemplate(' . $row->id . ')" class="btn btn-icon btn-danger" title="Delete Form">
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                    </form>';
                        } else {
                            $btn .= '<button type="button" class="btn btn-icon btn-secondary" title="Cannot delete (bookings exist)" disabled>
                                        <i class="feather icon-trash-2"></i>
                                    </button>';
                        }
                    }

                    if (auth()->user()->hasRole('Administrator') || Auth::user()->can('view templates')) {
                        if (!empty($row->data)) {
                            $btn .= '<a href="' . url('/form/' . $row->slug) . '" class="btn btn-icon btn-info ml-1" title="View Booking" target="_blank">
                                        <i class="feather icon-eye"></i>
                                    </a>';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }

        return view('admin.booking-template.index', compact('loginUser'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No templates selected.'], 400);
        }

        $templates = BookingTemplate::withCount('bookings')->whereIn('id', $ids)->get();

        $undeletable = $templates->filter(fn($t) => $t->bookings_count > 0);
        if ($undeletable->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Some templates cannot be deleted because they have bookings.'
            ], 400);
        }

        BookingTemplate::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected Templates Seleted Successfully.']);
    }



    public function templateSave(Request $request)
    {
        $data = $request->input('data');
        $templatename = $request->input('templatename');
        $templateid = $request->input('templateid');
        $status = $request->input('templatestatus');
        $vendorId = $request->input('vendorid');
        if (!empty($templateid)) {
            $template = BookingTemplate::find($templateid);
            if ($template) {
                $template->data = $data ? $data : "";
                $template->template_name = $templatename;
                $template->status = $status;
                $template->vendor_id = $vendorId;
                if (empty($template->slug)) {
                    $template->slug = Str::uuid();
                }
                $template->save();
                session()->flash('success', "Booking Template Updated Successfully.");
            } else {
                $template = BookingTemplate::create([
                    'data' => !empty($data) ? json_encode($data) : '',
                    'template_name' => $templatename,
                    'status' => $status,
                    'vendor_id' => $vendorId,
                    'created_by' => auth()->user()->id ?? 'NULL'
                ]);
                session()->flash('success', "Booking Template Added Successfully.");
            }
        } else {
            $template = BookingTemplate::create([
                'data' => !empty($data) ? json_encode($data) : '',
                'template_name' => $templatename,
                'status' => $status,
                'created_by' => auth()->user()->id ?? 'NULL',
                'vendor_id' => $vendorId,
                'slug' => Str::uuid(),
            ]);
            session()->flash('success', "Booking Template Added Successfully.");
        }
        return redirect()->route('template.list');
    }


    public function templateDelete($id)
    {
        $template = BookingTemplate::find($id);
        $templatename = $template->template_name;
        $template->delete();
        return response()->json(['success' => true]);
    }

    public function templateEdit($id)
    {
        $allusers  = $this->allUsers;
        $template = BookingTemplate::find($id);
        $loginId = getOriginalUserId();
        $loginUser = null;
        $activeVendor = Vendor::where('status', config('constants.status.active'))->get();

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('admin.booking-template.edit', ['templates' => $template, 'allusers' => $allusers, 'loginUser' => $loginUser, 'activeVendor' => $activeVendor]);
    }

    public function templateAdd()
    {
        $query = BookingTemplate::select(['id', 'template_name', 'created_at', 'created_by', 'slug'])->get();
        $allusers  = $this->allUsers;
        $loginId = getOriginalUserId();
        $loginUser = null;
        $activeVendor = Vendor::where('status', config('constants.status.active'))->get();
        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('admin.booking-template.add', compact('allusers', 'loginUser', 'query', 'activeVendor'));
    }

    public function copytemplate(Request $request)
    {
        if ($request->has('templateid')) {
            $templateid = $request['templateid'];
            $query = BookingTemplate::select(['id', 'template_name', 'created_at', 'created_by', 'slug', 'data'])->where('id', $templateid)->first();
            if ($query) {
                return response()->json([
                    'template_name' => $query->template_name,
                    'data' => $query->data
                ]);
            }
            return response()->json([]);
        }
    }
}
