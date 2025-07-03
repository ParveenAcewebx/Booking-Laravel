<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Support\Facades\Config;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryListingController extends Controller
{
    public function listing()
    {
        $categories = Category::where('status', config('constants.status.active'))
            ->withCount('services')
            ->get();
        return view('frontend.categoryListing', compact('categories'));
    }

    public function show()
    {
        return "data";
    }
}
