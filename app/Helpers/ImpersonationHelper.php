<?php

use Illuminate\Support\Facades\Cookie;

if (!function_exists('getOriginalUserId')) {
    function getOriginalUserId()
    {
        return session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');
    }
}
