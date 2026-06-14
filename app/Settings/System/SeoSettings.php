<?php

namespace App\Settings\System;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    // General SEO
    public string $meta_title_format;

    public array $meta_description;

    public array $meta_keywords;

    public ?string $canonical_url;

    public bool $robots_indexing;

    public bool $robots_following;

    public ?string $title_separator;

    public ?string $blog_title_format;

    public ?string $product_title_format;

    public ?string $category_title_format;

    public ?string $search_title_format;

    public ?string $author_title_format;

    // Open Graph
    public ?string $og_type;

    public array $og_title;

    public array $og_description;

    public ?string $og_image;

    public array $og_site_name;

    // Twitter Card
    public ?string $twitter_card_type;

    public ?string $twitter_site;

    public ?string $twitter_creator;

    public array $twitter_title;

    public array $twitter_description;

    public ?string $twitter_image;

    // Schema.org
    public ?string $schema_type;

    public ?string $schema_name;

    public array $schema_description;

    public ?string $schema_logo;

    // Sitemap
    public bool $sitemap_enabled;

    public bool $sitemap_include_pages;

    public bool $sitemap_include_posts;

    public bool $sitemap_include_categories;

    public bool $sitemap_include_tags;

    public ?string $robots_txt_content;

    public ?array $verification_codes;

    public ?string $head_additional_meta;

    public static function group(): string
    {
        return 'system_seo';
    }
}
