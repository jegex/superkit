<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class ErrorSettings extends Settings
{
    public array $custom_404_message;

    public array $custom_500_message;

    public static function group(): string
    {
        return 'system_error';
    }
}
