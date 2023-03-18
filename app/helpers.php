<?php

use App\Models\AdminUser;
use App\Models\User;

if (! function_exists('user')) {
    function user(string $guard = 'web'): User
    {
        $user = auth($guard)->user();
        if (! $user instanceof User) {
            throw new RuntimeException('No user authenticated.');
        }

        return $user;
    }
}

if (! function_exists('adminUser')) {
    function adminUser(string $guard = 'admin'): AdminUser
    {
        /** @var ?\App\Models\AdminUser */
        $adminUser = auth($guard)->user();
        if (! $adminUser instanceof AdminUser) {
            throw new RuntimeException('No admin user authenticated.');
        }

        return $adminUser;
    }
}
