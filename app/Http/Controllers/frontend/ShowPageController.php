<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages;  
class ShowPageController extends Controller
{
    public function show(Request $request, $slug){
        $pagedata = Pages::where('slug',$slug)->get()->first();
        if($pagedata){
            return view('frontend.page.PageView',compact('pagedata'));
        }        
    }
}
