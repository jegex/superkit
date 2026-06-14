<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system_legal.terms_url', '/terms');
        $this->migrator->add('system_legal.privacy_url', '/privacy');
        $this->migrator->add('system_legal.cookie_policy_url', '/cookie-policy');
        $this->migrator->add('system_legal.copyright_text', ['en' => '© '.date('Y').' SuperKit Starter. All rights reserved.']);
    }
};
