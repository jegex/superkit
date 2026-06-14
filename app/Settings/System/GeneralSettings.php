<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public array $name;

    public array $tagline;

    public array $description;

    public bool $is_maintenance;

    public static function group(): string
    {
        return 'system_general';
    }
}
