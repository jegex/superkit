<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class CompanySettings extends Settings
{
    public string $company_name;

    public string $company_email;

    public string $company_phone;

    public string $company_address;

    public static function group(): string
    {
        return 'system_company';
    }
}
