<?php

namespace App\Http\Controllers;

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

class BookingTemplateController extends Controller
{
    protected $allUsers;

    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index(Request $request)
    {
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        if ($request->ajax()) {
            $query = BookingTemplate::select(['id', 'template_name', 'created_at']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-success">Active</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (Auth::user()->can('edit forms')) {
                        $btn .= '<a href="' . route('template.edit', [$row->id]) . '" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit Form">
                                <i class="fas fa-pencil-alt"></i>
                             </a> ';
                    }

                    if (Auth::user()->can('delete forms')) {
                        $btn .= '<form action="' . route('template.delete', [$row->id]) . '" method="POST" id="deleteTemplate-' . $row->id . '" style="display:inline;">
                <input type="hidden" name="_method" value="DELETE">
                ' . csrf_field() . '
                <button type="button" onclick="return deleteTemplate(' . $row->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Form">
                    <i class="feather icon-trash-2"></i>
                </button>
            </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['status', 'action']) // no template_name here!
                ->make(true);
        }

        return view('booking-template.index', compact('loginUser'));
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
                $template->save();
            } else {
                $template = BookingTemplate::create([
                    'data' => $data,
                    'template_name' => $templatename
                ]);
            }
            session()->flash('success', " Booking Template Updated Successfully.");
        } else {
            $data = json_encode($data);
            $id = BookingTemplate::create([
                'data' => $data,
                'template_name' => $templatename
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
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('booking-template.edit', ['templates' => $template, 'allusers' => $allusers, 'loginUser' => $loginUser]);
    }

    public function templateAdd()
    {
        $allusers  = $this->allUsers;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('booking-template.add', compact('allusers', 'loginUser'));
    }
}
