<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Smpita\TypeAs\TypeAs;

if (! function_exists('user')) {
    function user(?string $guard = null): User
    {
        try {
            return TypeAs::class(
                User::class,
                TypeAs::class(Guard::class, Auth::guard($guard))->authenticate()
            );
        } catch (AuthenticationException $e) {
            report(new RuntimeException('User not authenticated.', previous: $e));

            throw $e;
        }
    }
}
