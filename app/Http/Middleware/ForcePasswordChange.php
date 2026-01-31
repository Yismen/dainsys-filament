<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();

        // Check if user needs to change password
        if ($user && $user->force_password_change) {
            // Allow access to the update password page and logout routes
            if (! $request->routeIs('filament.employee.pages.update-password') &&
                ! $request->routeIs('filament.employee.auth.logout')) {
                return redirect()->route('filament.employee.pages.update-password');
            }
        }

        return $next($request);
    }
}
