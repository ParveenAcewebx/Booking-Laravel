<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\Service;
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
use Illuminate\Support\Facades\Cookie;

class DashboardController extends Controller
{
    protected $allUsers;

    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index()
    {
        $allusers = User::orderBy('created_at', 'desc')->take(5)->get();
        $bookingForms = BookingTemplate::all();
        $services = Service::all();
        $bookings = Booking::all();
        $loginId = session('impersonate_original_user');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        return view('admin.layouts.dashboard', [
            'allusers' => $allusers,
            'bookingForms' => $bookingForms,
            'bookings' => $bookings,
            'loginUser' => $loginUser,
            'services' => $services
        ]);
    }

}
