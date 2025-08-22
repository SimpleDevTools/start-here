<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\User;
use Laravel\WorkOS\Http\Requests\AuthKitAccountDeletionRequest;
use Livewire\Component;

class DeleteUserForm extends Component
{
    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(AuthKitAccountDeletionRequest $request): void
    {
        $request->delete(using: fn (User $user) => $user->delete());

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.settings.delete-user-form');
    }
}
