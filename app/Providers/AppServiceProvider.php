<?php

namespace App\Providers;

use App\Services\MultiLanguageService;
use App\Settings\System\GeneralSettings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MultiLanguageService::class);
    }

    public function boot(): void
    {
        $service = $this->app->make(MultiLanguageService::class);
        $service->configurePackage();

        try {
            $general = app(GeneralSettings::class);

            $name = $general->name[app()->getLocale()] ?? null;

            if ($name) {
                config(['app.name' => $name]);
            }
        } catch (\Exception) {
            // Settings table might not exist yet
        }
    }
}
