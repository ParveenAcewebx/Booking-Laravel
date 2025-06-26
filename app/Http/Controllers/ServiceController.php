<?php

namespace App\Http\Controllers;

use App\Helpers\Shortcode;

use Illuminate\Http\Request;
use App\Models\BookingTemplate;
use App\Models\Booking;
use App\Models\User;
use App\Helpers\FormHelper;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        return view('service.index');
    }

    public function serviceAdd(Request $request)
    {
        return view('service.add');
    }

    public function servicestore(Request $request)
    {
        return view('service.add');
    }

    public function serviceDelete(Request $request)
    {
        return view('service.index');
    }

    public function serviceEdit(Request $request)
    {
        return view('service.edit');
    }

    public function serviceUpdate(Request $request)
    {
        return view('service.index');
    }
}
