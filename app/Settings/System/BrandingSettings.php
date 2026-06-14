<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class BrandingSettings extends Settings
{
    public ?string $logo;

    public ?string $logo_dark;

    public ?string $favicon;

    public static function group(): string
    {
        return 'system_branding';
    }
}
