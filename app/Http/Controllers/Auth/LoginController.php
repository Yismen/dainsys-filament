<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function login(Request $request): RedirectResponse|View
    {
        if (auth()->check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgotPassword(Request $request): RedirectResponse|View
    {
        if (auth()->check()) {
            return redirect('/');
        }

        return view('auth.forgot-password');
    }

    public function resetPassword(Request $request, string $token): RedirectResponse|View
    {
        if (auth()->check()) {
            return redirect('/');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }
}
