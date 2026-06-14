<?php

use App\Services\MultiLanguageService;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$settings = app(MultiLanguageService::class);

$frontendRoutes = fn () => require base_path('routes/frontend.php');

if ($settings->isEnabled()) {
    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ], $frontendRoutes);
} else {
    $frontendRoutes();
}
