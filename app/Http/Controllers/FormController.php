<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    public function index(){
        $allform=form::all();
        return view('form.formlist', ['allform' => $allform]);
    }

    public function formSave(Request $request){
        $data = $request->input('data');
        $fromname = $request->input('formname');
        $fromid = $request->input('formid');
        if (!empty($fromid)) {
            $form = Form::find($fromid);
            if($form) {
               $form->data = $data;
               $form->form_name = $fromname;
               $form->save();  
            }else{
                $form = Form::create([
                    'data' => $data,
                    'form_name' => $fromname
                ]);
            }
   
            session()->flash('success', $fromname." form edited successful!");
        } else {
            $data = json_encode($data);
            $id = Form::create([
            'data' => $data,
            'form_name' => $fromname
             ]);
            session()->flash('success', $fromname." form `added successful");
        }
    }
    

    public function formDelete($id) {
        $form = form::find($id);
        $formname=$form->form_name;
        $form->delete();
        return redirect('/form')->with('success',  $formname.' form deleted successful!');
    }

    public function formEdit($id) {
        $form = form::find($id);
        return view('form.formedit', ['forms' =>$form]);
    }
    public function formAdd() {
        // $form = form::find($id);
        return view('form.form');
    }
}
