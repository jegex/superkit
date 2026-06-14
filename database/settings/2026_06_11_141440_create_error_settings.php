<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system_error.custom_404_message', ['en' => 'Oops! This page seems to have vanished into the digital ether. Let\'s get you back on track.']);
        $this->migrator->add('system_error.custom_500_message', ['en' => 'We\'ve encountered an unexpected glitch. Our team has been notified and is working to restore service.']);
    }
};
