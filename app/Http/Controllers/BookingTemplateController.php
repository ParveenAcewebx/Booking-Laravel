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
    public function index(){
        $alltemplate = BookingTemplate::all();
        return view('booking-template.index', ['alltemplate' => $alltemplate]);
    }

    public function templateSave(Request $request)
    {
        $data = $request->input('data');
        $templatename = $request->input('templatename');
        $templateid = $request->input('templateid');
        if (!empty($templateid)) {
            $template = BookingTemplate::find($templateid);
            if($template) {
               $template->data = $data;
               $template->template_name = $templatename;
               $template->save();  
            }else{
                $template = BookingTemplate::create([
                    'data' => $data,
                    'template_name' => $templatename
                ]);
            }
            session()->flash('success', "The '". $templatename."' template has been successfully edited.");
        } else {
            $data = json_encode($data);
            $id = BookingTemplate::create([
                'data' => $data,
                'template_name' => $templatename
            ]);
            session()->flash('success',"The '". $templatename."' template has been successfully added.");
        }
    }
    
    public function templateDelete($id) {
        $template = BookingTemplate::find($id);
        $templatename =$template->template_name;
        $template->delete();
        return response()->json(['success' => true]);
    }

    public function templateEdit($id) {
        $template = BookingTemplate::find($id);
        return view('booking-template.edit', ['templates' =>$template]);
    }
    
    public function templateAdd() {
        return view('booking-template.add');
    }
}
