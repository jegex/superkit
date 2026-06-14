<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system_localization.default_language', 'en');

        $this->migrator->add('system_localization.multi_language_enabled', false);
        $this->migrator->add('system_localization.supported_locales', ['en']);
        $this->migrator->add('system_localization.multi_language_scope', 'frontend');
        $this->migrator->add('system_localization.hide_default_locale_in_url', true);
        $this->migrator->add('system_localization.auto_detect_language', false);
    }
};
