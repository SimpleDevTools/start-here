<?php

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Smpita\TypeAs\TypeAs;

if (! function_exists('user')) {
    function user(string $guard = 'web'): User
    {
        try {
            return TypeAs::class(
                User::class,
                TypeAs::class(SessionGuard::class, Auth::guard($guard))->authenticate()
            );
        } catch (AuthenticationException $e) {
            report(new RuntimeException('User not authenticated.', previous: $e));

            throw $e;
        }
    }
}
