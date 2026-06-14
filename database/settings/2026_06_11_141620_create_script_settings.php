<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Custom Scripts
        $this->migrator->add('system_scripts.header_scripts', null);
        $this->migrator->add('system_scripts.body_start_scripts', null);
        $this->migrator->add('system_scripts.body_end_scripts', null);
        $this->migrator->add('system_scripts.footer_scripts', null);

        // Cookie Consent
        $this->migrator->add('system_scripts.cookie_consent_enabled', true);
        $this->migrator->add('system_scripts.cookie_consent_text', ['en' => 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.']);
        $this->migrator->add('system_scripts.cookie_consent_button_text', ['en' => 'Accept']);
        $this->migrator->add('system_scripts.cookie_consent_policy_url', '/cookie-policy');

        // Custom Assets
        $this->migrator->add('system_scripts.custom_css', null);
        $this->migrator->add('system_scripts.custom_js', null);
    }
};
