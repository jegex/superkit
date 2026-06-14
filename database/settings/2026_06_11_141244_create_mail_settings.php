<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('system_mail', function (SettingsBlueprint $group) {
            $group->add('from_address', 'notifications@superduperstarter.com');
            $group->add('from_name', 'SuperKit Filament Starter');
            $group->add('reply_to_address', 'support@superduperstarter.com');
            $group->add('reply_to_name', 'SuperKit Support');

            $group->add('driver', 'smtp');
            $group->add('host', null);
            $group->add('port', 587);
            $group->add('encryption', 'tls');
            $group->addEncrypted('username', null);
            $group->addEncrypted('password', null);
            $group->add('timeout', 30);
            $group->add('local_domain', null); // Local domain for HELO command, usually not needed unless behind proxy

            $group->add('template_theme', 'default');
            $group->add('footer_text', '© '.date('Y').' SuperKit Starter. All rights reserved.');
            $group->add('logo_path', 'sites/email-logo.png');
            $group->add('primary_color', '#2D2B8D');
            $group->add('secondary_color', '#FFC903');

            $group->add('queue_emails', true);
            $group->add('queue_name', 'emails');
            $group->add('queue_connection', 'database');
            $group->add('rate_limiting', [
                'enabled' => true,
                'attempts' => 5,
                'per_minutes' => 1,
            ]);

            $group->add('notifications_enabled', true);
            $group->add('notification_types', [
                'account' => true,
                'system' => true,
                'marketing' => false,
                'blog' => false,
            ]);

            $group->add('test_mode', false);
            $group->add('log_channel', 'stack');
            $group->add('test_to_address', '');

            $group->add('providers', [
                'mailgun' => [
                    'domain' => null,
                    'secret' => null,
                    'endpoint' => 'api.mailgun.net',
                ],
                'postmark' => [
                    'token' => null,
                ],
                'ses' => [
                    'key' => null,
                    'secret' => null,
                    'region' => 'us-east-1',
                ],
            ]);
        });
    }
};
