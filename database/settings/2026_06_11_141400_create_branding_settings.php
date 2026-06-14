<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system_branding.logo', 'sites/logo.png');
        $this->migrator->add('system_branding.logo_dark', null);
        $this->migrator->add('system_branding.favicon', 'sites/logo.ico');
    }
};
