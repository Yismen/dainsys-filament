<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocaleMiddleware
{
    /**
     * @param  Closure(Request): (Response|StreamedResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        App::setLocale($locale);

        session()->put('locale', $locale);

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        $supportedLocales = config('localization.locales', ['en']);
        $defaultLocale = config('localization.default_locale', 'en');

        $locale = $request->cookie('filament_language_switch_locale')
            ?? session()->get('locale')
            ?? $request->query('locale')
            ?? $request->input('locale');

        if ($locale === null) {
            $locale = $request->getPreferredLanguage($supportedLocales);
        }

        if ($locale === null || ! in_array($locale, $supportedLocales, true)) {
            $locale = $defaultLocale;
        }

        return $locale;
    }
}
