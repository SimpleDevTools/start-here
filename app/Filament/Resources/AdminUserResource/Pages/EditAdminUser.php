<?php

namespace App\Filament\Resources\AdminUserResource\Pages;

use App\Filament\Resources\AdminUserResource;
use App\Models\AdminUser;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminUser extends EditRecord
{
    protected static string $resource = AdminUserResource::class;

    /**
     * @return array<\Filament\Pages\Actions\Action>
     */
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden($this->record->is(adminUser())),
        ];
    }

    public function afterSave(): void
    {
        assert($this->record instanceof AdminUser);

        if ($this->record->wasChanged('password')) {
            if ($this->record->is(adminUser())) {
                session()->put('password_hash_admin', $this->record->password);
            }
        }

        $this->redirect(action(self::class, $this->record));
    }
}
