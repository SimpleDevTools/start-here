<?php

use App\Http\Livewire\Contact;
use App\Http\Livewire\Home;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('page-cache')->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/contact', Contact::class)->name('contact');
});
