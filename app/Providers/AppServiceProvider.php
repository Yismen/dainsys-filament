<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Jeffgreco13\FilamentBreezy\Livewire\SanctumTokens;
use League\Flysystem\Filesystem;
use Livewire\Livewire;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme(scheme: 'https');

        Model::preventAccessingMissingAttributes(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
        Model::preventLazyLoading(! app()->isProduction());

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch): void {
            $panelSwitch
                ->modalHeading('Modules')
                // ->areUserProvidedPanelsValid
                ->simple();
        });

        Livewire::component('sanctum_tokens', SanctumTokens::class);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch
                ->locales(['es', 'en'])
                ->circular()
                ->renderHook('panels::global-search.before');
        });

        Storage::extend('dropbox', function ($app, $config) {
            $client = new Client($config['accessToken']);

            $adapter = new DropboxAdapter($client);
            $driver = new Filesystem($adapter, ['case_sensitive' => false]);

            return new FilesystemAdapter($driver, $adapter);
        });
    }
}
