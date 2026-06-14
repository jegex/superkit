<?php

namespace App\Services;

use App\Enums\MultiLanguageScope;
use App\Models\Locale;
use App\Settings\System\LocalizationSettings;
use Illuminate\Http\Request;

class MultiLanguageService
{
    public function __construct(
        private LocalizationSettings $settings,
    ) {}

    public function isEnabled(): bool
    {
        try {
            return $this->settings->multi_language_enabled;
        } catch (\Exception) {
            return false;
        }
    }

    public function getSupportedLocales(): array
    {
        try {
            return $this->settings->supported_locales;
        } catch (\Exception) {
            return ['en'];
        }
    }

    public function getDefaultLocale(): string
    {
        try {
            return $this->settings->default_language;
        } catch (\Exception) {
            return 'en';
        }
    }

    public function getScope(): MultiLanguageScope
    {
        try {
            return $this->settings->multi_language_scope;
        } catch (\Exception) {
            return MultiLanguageScope::All;
        }
    }

    public function shouldLocalize(Request $request): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        if ($this->getScope() === MultiLanguageScope::All) {
            return true;
        }

        return ! str_starts_with($request->path(), 'admin');
    }

    public function configurePackage(): void
    {
        if (! $this->isEnabled()) {
            config([
                'laravellocalization.supportedLocales' => [
                    $this->getDefaultLocale() => [
                        'name' => 'Default',
                        'native' => 'Default',
                        'regional' => $this->getDefaultLocale(),
                    ],
                ],
                'laravellocalization.hideDefaultLocaleInURL' => true,
                'laravellocalization.useAcceptLanguageHeader' => false,
            ]);

            return;
        }

        config([
            'laravellocalization.supportedLocales' => $this->buildSupportedLocalesConfig(),
            'laravellocalization.hideDefaultLocaleInURL' => $this->settings->hide_default_locale_in_url,
            'laravellocalization.useAcceptLanguageHeader' => $this->settings->auto_detect_language,
        ]);
    }

    private function buildSupportedLocalesConfig(): array
    {
        $locales = Locale::whereIn('code', $this->getSupportedLocales())->get();
        $config = [];

        foreach ($locales as $locale) {
            $config[$locale->code] = [
                'name' => $locale->name,
                'native' => $locale->native,
                'regional' => $locale->regional ?: $locale->code,
            ];
        }

        return $config;
    }
}
