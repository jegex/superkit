<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class LegalSettings extends Settings
{
    public string $terms_url;

    public string $privacy_url;

    public string $cookie_policy_url;

    public array $copyright_text;

    public static function group(): string
    {
        return 'system_legal';
    }
}
