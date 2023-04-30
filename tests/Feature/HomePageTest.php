<?php

namespace Tests\Feature;

use App\Http\Livewire\Home;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_route_is_registered(): void
    {
        $this->assertEquals('/', route('home', absolute: false));
        $this->assertEquals('/', action(Home::class, absolute: false));
    }

    public function test_expected_view_is_returned(): void
    {
        $this
            ->get(route('home'))
            ->assertSuccessful()
            ->assertSeeLivewire(Home::class);
    }
}
