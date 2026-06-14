<?php

use App\Settings\System\BrandingSettings;
use App\Settings\System\CompanySettings;
use App\Settings\System\GeneralSettings;
use App\Settings\System\LegalSettings;
use App\Settings\System\LocalizationSettings;
use App\Settings\System\SeoSettings;
use App\Settings\System\SocialSettings;
use Illuminate\Support\Facades\Storage;

if (! function_exists('defaultLocale')) {
    function defaultLocale(): string
    {
        return app(LocalizationSettings::class)->default_language ?? 'en';
    }
}

if (! function_exists('setting')) {
    function setting(string $class, string $property, mixed $default = null): mixed
    {
        try {
            $value = app($class)->$property;
            $currentLocale = LaravelLocalization::getCurrentLocale();

            if (is_array($value)) {
                return $value[$currentLocale] ?? $value[defaultLocale()];
            }

            return $value ?? $default;
        } catch (Exception) {
            return $default;
        }
    }
}

if (! function_exists('settingRaw')) {
    function settingRaw(string $class, string $property, mixed $default = null): mixed
    {
        try {
            return app($class)->$property ?? $default;
        } catch (Exception) {
            return $default;
        }
    }
}

if (! function_exists('siteName')) {
    function siteName(): string
    {
        return setting(GeneralSettings::class, 'name', config('app.name'));
    }
}

if (! function_exists('storageUrl')) {
    function storageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('siteLogo')) {
    function siteLogo(): ?string
    {
        $path = setting(BrandingSettings::class, 'logo');

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return storageUrl($path);
    }
}

if (! function_exists('siteLogoDark')) {
    function siteLogoDark(): ?string
    {
        $path = setting(BrandingSettings::class, 'logo_dark');

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return storageUrl($path);
    }
}

if (! function_exists('siteFavicon')) {
    function siteFavicon(): ?string
    {
        return storageUrl(setting(BrandingSettings::class, 'favicon'));
    }
}

if (! function_exists('siteTagline')) {
    function siteTagline(): ?string
    {
        return setting(GeneralSettings::class, 'tagline');
    }
}

if (! function_exists('siteDescription')) {
    function siteDescription(): ?string
    {
        return setting(GeneralSettings::class, 'description');
    }
}

if (! function_exists('socialLinks')) {
    function socialLinks(): array
    {
        try {
            $settings = app(SocialSettings::class);

            return array_filter([
                'facebook' => $settings->facebook_url,
                'twitter' => $settings->twitter_url,
                'instagram' => $settings->instagram_url,
                'linkedin' => $settings->linkedin_url,
                'youtube' => $settings->youtube_url,
            ]);
        } catch (Exception) {
            return [];
        }
    }
}

if (! function_exists('companyInfo')) {
    function companyInfo(): array
    {
        try {
            $settings = app(CompanySettings::class);

            return [
                'name' => $settings->company_name,
                'email' => $settings->company_email,
                'phone' => $settings->company_phone,
                'address' => $settings->company_address,
            ];
        } catch (Exception) {
            return [];
        }
    }
}

if (! function_exists('legalLinks')) {
    function legalLinks(): array
    {
        try {
            $settings = app(LegalSettings::class);

            return array_filter([
                'terms' => $settings->terms_url,
                'privacy' => $settings->privacy_url,
                'cookie' => $settings->cookie_policy_url,
            ]);
        } catch (Exception) {
            return [];
        }
    }
}

if (! function_exists('seoMeta')) {
    function seoMeta(): array
    {
        try {
            $settings = app(SeoSettings::class);

            return [
                'meta_description' => $settings->meta_description[app()->getLocale()] ?? null,
                'meta_keywords' => $settings->meta_keywords[app()->getLocale()] ?? null,
                'canonical_url' => $settings->canonical_url,
                'robots_indexing' => $settings->robots_indexing,
                'robots_following' => $settings->robots_following,
                'title_separator' => $settings->title_separator ?? '•',
                'og_type' => $settings->og_type,
                'og_title' => $settings->og_title[app()->getLocale()] ?? null,
                'og_description' => $settings->og_description[app()->getLocale()] ?? null,
                'og_image' => storageUrl($settings->og_image),
                'og_site_name' => $settings->og_site_name[app()->getLocale()] ?? null,
                'twitter_card_type' => $settings->twitter_card_type,
                'twitter_site' => $settings->twitter_site,
                'twitter_creator' => $settings->twitter_creator,
                'twitter_title' => $settings->twitter_title[app()->getLocale()] ?? null,
                'twitter_description' => $settings->twitter_description[app()->getLocale()] ?? null,
                'twitter_image' => storageUrl($settings->twitter_image),
                'schema_type' => $settings->schema_type,
                'schema_name' => $settings->schema_name,
                'schema_description' => $settings->schema_description[app()->getLocale()] ?? null,
                'schema_logo' => $settings->schema_logo,
                'verification_codes' => $settings->verification_codes,
                'head_additional_meta' => $settings->head_additional_meta,
            ];
        } catch (Exception) {
            return [];
        }
    }
}
