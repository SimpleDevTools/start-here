<?php

namespace App\Http\Livewire;

use Filament\Notifications\Notification;
use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    public function mount(): void
    {
        Notification::make()
            ->title('Welcome')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.home');
    }
}
