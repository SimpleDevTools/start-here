<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class AdminUser extends User implements FilamentUser
{
    use Notifiable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['password', 'remember_token'];

    public function canAccessFilament(): bool
    {
        return true;
    }
}
