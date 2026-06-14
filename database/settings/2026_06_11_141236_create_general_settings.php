<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system_general.name', [
            'en' => 'SuperKit',
        ]);
        $this->migrator->add('system_general.tagline', [
            'en' => 'Build Something Amazing',
            'id' => 'Bangun Sesuatu yang Luar Biasa',
        ]);
        $this->migrator->add('system_general.description', [
            'en' => 'A powerful Laravel starter kit with Filament admin panel, multi-language CMS, and more.',
            'id' => 'Starter kit Laravel yang canggih dengan panel admin Filament, CMS multi-bahasa, dan lainnya.',
        ]);
        $this->migrator->add('system_general.is_maintenance', false);
    }
};
