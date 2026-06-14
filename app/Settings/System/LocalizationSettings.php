<?php

namespace App\Settings\System;

use App\Enums\MultiLanguageScope;
use Spatie\LaravelSettings\Settings;

class LocalizationSettings extends Settings
{
    public string $default_language;

    // Multi Language
    public bool $multi_language_enabled = false;

    public array $supported_locales = ['en'];

    public MultiLanguageScope $multi_language_scope = MultiLanguageScope::Frontend;

    public bool $hide_default_locale_in_url = true;

    public bool $auto_detect_language = true;

    public static function group(): string
    {
        return 'system_localization';
    }
}
