<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BookingTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class BookingTemplateController extends Controller
{
    public function index()
    {
        $alltemplate = BookingTemplate::all();
        $allusers  = User::all();
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('booking-template.index', ['alltemplate' => $alltemplate, 'allusers' => $allusers, 'loginUser' => $loginUser]);
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
            session()->flash('success', "The '" . $templatename . "' template has been successfully edited.");
        } else {
            $data = json_encode($data);
            $id = BookingTemplate::create([
                'data' => $data,
                'template_name' => $templatename
            ]);
            session()->flash('success', "The '" . $templatename . "' template has been successfully added.");
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
        $allusers  = User::all();
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
        $allusers  = User::all();
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('booking-template.add', compact('allusers','loginUser'));
    }
}
