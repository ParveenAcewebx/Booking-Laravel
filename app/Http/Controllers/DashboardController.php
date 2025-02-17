<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Bookingform;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use DB; 
use Carbon\Carbon; 
use Mail; 
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(){
        $allusers = User::all();
        $bookingForms = Bookingform::all();
        $bookings = Booking::all();
        return view('layouts.dashboard', ['allusers' => $allusers,'bookingForms'=>$bookingForms,'bookings'=>$bookings]);
    }

}
