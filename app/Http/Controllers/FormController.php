<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bookingform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    public function index(){
        $allform=Bookingform::all();
        return view('form.formlist', ['allform' => $allform]);
    }

    public function formSave(Request $request)
    {
        $data = $request->input('data');
        $fromname = $request->input('formname');
        $fromid = $request->input('formid');
        if (!empty($fromid)) {
            $form = Bookingform::find($fromid);
            if($form) {
               $form->data = $data;
               $form->form_name = $fromname;
               $form->save();  
            }else{
                $form = Bookingform::create([
                    'data' => $data,
                    'form_name' => $fromname
                ]);
            }
            session()->flash('success', "The '". $fromname."' form has been successfully edited.");
        } else {
            $data = json_encode($data);
            $id = Bookingform::create([
            'data' => $data,
            'form_name' => $fromname
             ]);
            session()->flash('success',"The '". $fromname."' form has been successfully added.");
        }
    }
    
    public function formDelete($id) {
        $form = Bookingform::find($id);
        $formname=$form->form_name;
        $form->delete();
        return response()->json(['success' => true]);

    }

    public function formEdit($id) {
        $form = Bookingform::find($id);
        return view('form.formedit', ['forms' =>$form]);
    }
    public function formAdd() {
        return view('form.form');
    }
}
