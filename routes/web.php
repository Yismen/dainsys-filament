<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\MyMailingSubscriptions;
use App\Livewire\MyTicketsManagement;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('password.request');

Route::get('/reset-password/{token}', [LoginController::class, 'resetPassword'])->name('password.reset');

Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');
Route::view('/cookies', 'cookies')->name('cookies');

Route::livewire('/my-subscriptions', MyMailingSubscriptions::class)
    ->middleware('auth')
    ->name('my-subscriptions');

Route::livewire('/my-tickets-management', MyTicketsManagement::class)
    ->middleware('auth')
    ->name('my-tickets-management');
