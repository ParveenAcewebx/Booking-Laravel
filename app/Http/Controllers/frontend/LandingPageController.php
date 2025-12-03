<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Service;

class LandingPageController extends Controller
{
   public function index(){
    $vendors = Vendor::where('status', 1)->get();
    $service = Service::where('status', 1)->get();

    return view('frontend.landing',compact('vendors','service'));
   }
}
