<?php
use Illuminate\Support\Facades\Auth;

// 管理员鉴权
if (!function_exists('isSuperadmin')) {
    //超级管理员
    function isSuperadmin(): bool
    {
        return Auth::check() && Auth::id() == 1;
    }
}
