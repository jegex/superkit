<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class ScriptSettings extends Settings
{
    // Custom Scripts
    public ?string $header_scripts;

    public ?string $body_start_scripts;

    public ?string $body_end_scripts;

    public ?string $footer_scripts;

    // Cookie Consent
    public bool $cookie_consent_enabled;

    public array $cookie_consent_text;

    public array $cookie_consent_button_text;

    public ?string $cookie_consent_policy_url;

    // Custom Assets
    public ?string $custom_css;

    public ?string $custom_js;

    public static function group(): string
    {
        return 'system_scripts';
    }
}
