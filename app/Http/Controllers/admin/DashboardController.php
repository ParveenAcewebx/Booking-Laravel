<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\Service;
use App\Models\Setting;
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

    public function index(Request $request)
    {
        $countUsers = $this->allUsers;
        $allusers = User::orderBy('created_at', 'desc')->take(5)->get();
        $bookingForms = BookingTemplate::all();
        $settings = Setting::pluck('value', 'key')->toArray();
        $dateFormat = $settings['date_format'];
        $timeFormat = $settings['time_format'];
        $timezone   = $settings['timezone'];
        $dateRange = $request->date_range;
          if ($dateRange) {
            [$start, $end] = explode(" - ", $dateRange);
            $start = Carbon::parse($start, $timezone)->startOfDay();
            $end = $start->copy()->addDays(6)->endOfDay();

            $bookings = Booking::whereBetween('booking_datetime', [$start, $end])
                ->orderBy('booking_datetime', 'asc')
                ->get();
        } else {
            $start = now($timezone)->startOfWeek();
            $end   = now($timezone)->endOfWeek();

            $bookings = Booking::whereBetween('booking_datetime', [$start, $end])
                ->orderBy('booking_datetime', 'asc')
                ->get();
        }
        $groupedBookings = $bookings->groupBy(function ($item) use ($dateFormat, $timezone) {
            return Carbon::parse($item->booking_datetime)
                ->timezone($timezone)
                ->format($dateFormat);
        });
        
        $chartLabels = $groupedBookings->keys()->values();
        $chartValues = $groupedBookings->map->count()->values();
        $services = Service::all();
        $bookings = Booking::all();
        $loginId = getOriginalUserId();
        $loginUser = null;
        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('admin.layouts.dashboard', [
            'totalUsers' => $countUsers,
            'allusers' => $allusers,
            'bookingForms' => $bookingForms,
            'bookings' => $bookings,
            'loginUser' => $loginUser,
            'services' => $services,
            'chartLabels'  => $chartLabels,
            'chartValues'  => $chartValues,
        ]);
    }
}
