<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function login(Request $request): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgotPassword(Request $request): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.forgot-password');
    }

    public function resetPassword(Request $request, string $token): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function adminResetPassword(Request $request, User $user): View
    {
        $token = Password::createToken($user);

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }
}
