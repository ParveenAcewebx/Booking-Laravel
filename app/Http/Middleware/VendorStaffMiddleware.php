<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VendorStaffAssociation;
use Illuminate\Support\Facades\Auth;

class VendorStaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isVendorStaff = false;

        if (Auth::check()) {
            $user = Auth::user();
            // Check if user has role 'Staff' AND exists in VendorStaffAssociation
            if ($user->hasRole('Staff')) {
                $isVendorStaff = VendorStaffAssociation::where('user_id', $user->id)->exists();
            }
        }

        // Share variable with all views (header included)
        view()->share('isVendorStaff', $isVendorStaff);

        return $next($request);
    }
}
