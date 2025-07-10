<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Http\Request;
use App\Models\User;
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
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        if ($request->ajax()) {
            $query = BookingTemplate::select(['id', 'template_name', 'created_at', 'created_by','slug']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-success">Active</span>';
                })
                ->addColumn('created_by', function ($row) {
                    return $row->created_by ?? '';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (Auth::user()->can('edit forms')) {
                        $btn .= '<a href="' . route('template.edit', [$row->id]) . '" class="btn btn-icon btn-success" title="Edit Form">
                        <i class="fas fa-pencil-alt"></i>
                    </a> ';
                    }

                    if (Auth::user()->can('delete forms')) {
                        $btn .= '<form action="' . route('template.delete', [$row->id]) . '" method="POST" id="deleteTemplate-' . $row->id . '" style="display:inline;">
                        <input type="hidden" name="_method" value="DELETE">
                        ' . csrf_field() . '
                        <button type="button" onclick="return deleteTemplate(' . $row->id . ')" class="btn btn-icon btn-danger" title="Delete Form">
                            <i class="feather icon-trash-2"></i>
                        </button>
                    </form>';
                    }

                    if (auth()->user()->hasRole('Administrator')) {
                        $btn .= '<a href="' . url('/form/' . $row->slug) . '" class="btn btn-icon btn-info ml-1" title="View Booking" target="_blank">
                        <i class="feather icon-eye"></i>
                    </a>';
                    }

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.booking-template.index', compact('loginUser'));
    }

    public function templateSave(Request $request)
    {
        $data = $request->input('data');
        $templatename = $request->input('templatename');
        $templateid = $request->input('templateid');
        if (!empty($templateid)) {
            $template = BookingTemplate::find($templateid);
            if ($template) {
                $template->data = $data;
                $template->template_name = $templatename;
                if (empty($template->slug)) {
                    $template->slug = Str::uuid();
                }
                $template->save();
                session()->flash('success', "Booking Template Updated Successfully.");
            } else {
                $template = BookingTemplate::create([
                    'data' => json_encode($data),
                    'template_name' => $templatename,
                    'created_by' => auth()->user()->name ?? 'NULL'
                ]);
                session()->flash('success', "Booking Template Added Successfully.");
            }
        } else {
            $template = BookingTemplate::create([
                'data' => json_encode($data),
                'template_name' => $templatename,
                'created_by' => auth()->user()->name ?? 'NULL',
                'slug' => Str::uuid(),
            ]);
            session()->flash('success', "Booking Template Added Successfully.");
        }
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
        $loginId = session('impersonate_original_user');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('admin.booking-template.edit', ['templates' => $template, 'allusers' => $allusers, 'loginUser' => $loginUser]);
    }

    public function templateAdd()
    {
        $allusers  = $this->allUsers;
        $loginId = session('impersonate_original_user');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('admin.booking-template.add', compact('allusers', 'loginUser'));
    }
}
