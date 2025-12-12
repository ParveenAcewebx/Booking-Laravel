<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
        $logoutTime = config('app.automatic_logout_time');        
        $countUsers   = $this->allUsers;
        $allusers     = User::orderBy('created_at', 'desc')->take(5)->get();
        $bookingForms = BookingTemplate::all();
        $services     = Service::all();
        $settings     = Setting::pluck('value', 'key')->toArray();
        $dateFormat = $settings['date_format'];
        $timezone   = $settings['timezone'];
        $selectedDateRange = $request->date_range;
        if ($selectedDateRange) {
            [$start, $end] = explode(" - ", $selectedDateRange);
            $start = Carbon::parse($start, $timezone)->startOfDay();
            $end   = Carbon::parse($end, $timezone)->endOfDay();
        } else {
            $start = now($timezone)->subDays(6)->startOfDay();
            $end   = now($timezone)->endOfDay();
            $selectedDateRange = $start->format('Y-m-d') . " - " . $end->format('Y-m-d');
        }

        $bookingsFiltered = Booking::whereBetween('booking_datetime', [$start, $end])
            ->orderBy('booking_datetime', 'asc')
            ->get();

        $groupedBookings = $bookingsFiltered->groupBy(function ($item) use ($timezone, $dateFormat) {
            return Carbon::parse($item->booking_datetime)
                ->timezone($timezone)
                ->format($dateFormat);
        });

        $chartLabels = $groupedBookings->keys()->values();
        $chartValues = $groupedBookings->map->count()->values();

        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.layouts.dashboard', [
            'totalUsers'        => $countUsers,
            'allusers'          => $allusers,
            'bookingForms'      => $bookingForms,
            'services'          => $services,
            'bookings'          => Booking::all(),
            'chartLabels'       => $chartLabels,
            'chartValues'       => $chartValues,
            'loginUser'         => $loginUser,
            'selectedDateRange' => $selectedDateRange,
            'logoutTime'        => $logoutTime,
        ]);
    }
}
