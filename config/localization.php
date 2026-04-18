<?php

return [
    /**
     * Supported locales for the application.
     */
    'locales' => ['en', 'es'],

    /**
     * Default locale used when no other locale is detected.
     */
    'default_locale' => env('APP_LOCALE', 'en'),

    /**
     * Fallback locale when a translation key is missing.
     */
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /**
     * Whether to enable per-panel locale (requires additional setup).
     */
    'per_panel_locale' => false,
];
