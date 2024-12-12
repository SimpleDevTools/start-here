<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::redirect('/login', '/dashboard/login')->name('login');
