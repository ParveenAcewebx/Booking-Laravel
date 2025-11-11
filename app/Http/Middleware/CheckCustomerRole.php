<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CheckCustomerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestUri = $request->getRequestUri();
        $user = auth()->user();

        if (str_starts_with($requestUri, '/admin/switch-back')) {
            $originalUserId = session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');
            if ($originalUserId) {
                $originalUser = User::find($originalUserId);
                if ($originalUser) {
                    Auth::login($originalUser);
                    session()->forget('impersonate_original_user');
                   $switchback = session('impersonate_original_switch_back');
                    Cookie::queue(Cookie::forget('impersonate_original_user'));
                    if ($user->hasRole('Staff') && $user->staff && $user->staff->primary_staff == 1) {
                        if ($switchback === 'vendor') {
                              session()->forget('impersonate_original_switch_back');
                            return redirect('/admin/vendors');
                        }
                        return redirect('/admin/staffs');
                    }
                    return redirect('/admin');
                }
            }
        }

        if ($user->hasRole('Customer')) {
            return redirect()->route('home');
        }


        return $next($request);
    }
}
