<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system_company.company_name', 'SuperKit Starter');
        $this->migrator->add('system_company.company_email', 'hello@superduperstarter.com');
        $this->migrator->add('system_company.company_phone', '+1 (800) 123-4567');
        $this->migrator->add('system_company.company_address', 'Innovation Tower, 101 Tech Boulevard, Digital City, 10101');
    }
};
