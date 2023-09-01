<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    public int $count = 0;

    public function render(): View
    {
        return view('livewire.home');
    }
}
