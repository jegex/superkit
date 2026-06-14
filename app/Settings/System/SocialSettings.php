<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings
{
    // Social Profiles
    public ?string $facebook_url;

    public ?string $twitter_url;

    public ?string $instagram_url;

    public ?string $linkedin_url;

    public ?string $youtube_url;

    public ?string $pinterest_url;

    public ?string $tiktok_url;

    // Social Sharing
    public bool $social_share_enabled;

    public array $social_share_platforms;

    public ?string $social_share_default_image;

    public static function group(): string
    {
        return 'system_social';
    }
}
