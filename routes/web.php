<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

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

Route::get('/login', function (): View|RedirectResponse {
    if (Auth::check()) {
        return redirect('/');
    }

    return view('auth.login');
})->name('login');

Route::post('/logout', function (Request $request): RedirectResponse {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/forgot-password', function (): View|RedirectResponse {
    if (Auth::check()) {
        return redirect('/');
    }

    return view('auth.forgot-password');
})->name('password.request');

Route::get('/reset-password/{token}', function (Request $request, string $token): View|RedirectResponse {
    if (Auth::check()) {
        return redirect('/');
    }

    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email'),
    ]);
})->name('password.reset');
