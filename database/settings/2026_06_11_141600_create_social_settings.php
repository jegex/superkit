<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Social Profiles
        $this->migrator->add('system_social.facebook_url', null);
        $this->migrator->add('system_social.twitter_url', null);
        $this->migrator->add('system_social.instagram_url', null);
        $this->migrator->add('system_social.linkedin_url', null);
        $this->migrator->add('system_social.youtube_url', null);
        $this->migrator->add('system_social.pinterest_url', null);
        $this->migrator->add('system_social.tiktok_url', null);

        // Social Sharing
        $this->migrator->add('system_social.social_share_enabled', true);
        $this->migrator->add('system_social.social_share_platforms', ['facebook', 'twitter', 'linkedin']);
        $this->migrator->add('system_social.social_share_default_image', 'sites/share-image.png');
    }
};
