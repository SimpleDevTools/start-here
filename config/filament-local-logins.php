<?php

use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;
use Smpita\TypeAs\TypeAs;

return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', TypeAs::string(env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', ''))))),
            'login_page' => LoginPage::class,
        ],
    ],
];
