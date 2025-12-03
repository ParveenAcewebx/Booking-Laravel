<?php

namespace App\Http\Controllers\API\V2;



use Illuminate\Http\Request;
use App\Models\User;
use App\Models\form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class APIControllerV2 extends Controller
{
    public function loginUserAPI(Request $request){
            try {
                $validatedData = $request->validate([
                    'email' => 'required|email|exists:users,email',  
                    'password' => 'required|min:8',                   
                ]);
                $user = User::where('email', $request->email)->first();
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'message' => 'Wrong credentials',
                        
                    ]);
                }
                $token = $user->createToken('API Token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful',
                    'token' =>  $token,
                ]);
            }catch(ValidationException $e) {
        
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),  
                ], 422); 
            }
        
    }
    public function getForm(Request $request, $id){
            $form = Form::find($id);
            if (!$form) {
                return response()->json([
                    'message' => 'Form not found.',
                ], 404); 
            }
            $formData = json_decode($form->data, true); 
            $array = json_decode($form->data, true);

            $output_array = [];
                $current_step = [
                    'heading' => 'Booking 1',
                    'description' => 'Step 1 description',
                    'fields' => []
                ];

            $counter = 1; 

            foreach ($array as $item) {
                
                if ($item['type'] != 'newsection') {
                    $current_step['fields'][] = $item;
                }
                
                if ($item['type'] == 'newsection') {
                    $output_array[] = $current_step;

                    $counter++;
                    
                    $current_step = [
                        'heading' => 'Booking ' . $counter,
                        'description' => 'Step ' . $counter . ' description',
                        'fields' => []
                    ];
                }
            }

            if ($current_step) {
                $output_array[] = $current_step;
            }

        return response()->json($output_array );  
           
    }  
}
