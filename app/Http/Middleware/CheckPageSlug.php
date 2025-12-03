<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pages;

class CheckPageSlug
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');

        if (Pages::where('slug', $slug)->exists()) {
            return $next($request);
        }

        return abort(404, 'Page not found');
    }
}
