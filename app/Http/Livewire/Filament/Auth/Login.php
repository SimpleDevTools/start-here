<?php

namespace App\Http\Livewire\Filament\Auth;

use App\Models\AdminUser;
use Filament\Facades\Filament;
use Filament\Http\Livewire\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public function localLoginAs(string $email): LoginResponse
    {
        if (! app()->environment('local')) {
            abort(403);
        }

        abort_unless(in_array($email, $this->localLoginEmails()), 404);

        Filament::auth()->login(AdminUser::query()->where('email', $email)->firstOrFail(), true);

        session()->regenerate();

        return app(LoginResponse::class);
    }

    /**
     * @return array<string>
     */
    public function localLoginEmails(): array
    {
        $localLoginEmails = [];

        if (app()->environment('local')) {
            $localLoginEmails = config('filament.auth.local_login_emails');
        }

        assert(is_array($localLoginEmails));

        return $localLoginEmails;
    }
}
