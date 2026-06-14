<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // General SEO
        $this->migrator->add('system_seo.meta_title_format', '{page_title} {separator} {site_name}');
        $this->migrator->add('system_seo.meta_description', ['en' => 'Accelerate your Laravel development with SuperDuper Filament Starter — featuring enterprise-ready plugins, seamless admin interfaces, and powerful developer tools in one package.']);
        $this->migrator->add('system_seo.meta_keywords', ['en' => 'filament admin, laravel development, admin dashboard, filament starter, developer toolkit, rapid application development, enterprise cms, content management, user management']);
        $this->migrator->add('system_seo.canonical_url', null);
        $this->migrator->add('system_seo.robots_indexing', true);
        $this->migrator->add('system_seo.robots_following', true);
        $this->migrator->add('system_seo.title_separator', '•');
        $this->migrator->add('system_seo.blog_title_format', '{post_title} {separator} {site_name}');
        $this->migrator->add('system_seo.product_title_format', '{product_name} • Filament Component {separator} {site_name}');
        $this->migrator->add('system_seo.category_title_format', '{category_name} Solutions & Resources {separator} {site_name}');
        $this->migrator->add('system_seo.search_title_format', 'Results for "{search_term}" {separator} Developer Resources {separator} {site_name}');
        $this->migrator->add('system_seo.author_title_format', 'Expert Content by {author_name} {separator} {site_name}');

        // Open Graph
        $this->migrator->add('system_seo.og_type', 'website');
        $this->migrator->add('system_seo.og_title', ['en' => '{page_title} | SuperDuper Filament Starter']);
        $this->migrator->add('system_seo.og_description', ['en' => 'Transform your Laravel development workflow with our Filament toolkit. Built for developers who demand excellence.']);
        $this->migrator->add('system_seo.og_image', 'sites/social-card.png');
        $this->migrator->add('system_seo.og_site_name', ['en' => 'SuperDuper Filament Starter']);

        // Twitter Card
        $this->migrator->add('system_seo.twitter_card_type', 'summary_large_image');
        $this->migrator->add('system_seo.twitter_site', '@superduper');
        $this->migrator->add('system_seo.twitter_creator', '@superduper');
        $this->migrator->add('system_seo.twitter_title', ['en' => '{page_title} | Professional Developer Tools']);
        $this->migrator->add('system_seo.twitter_description', ['en' => 'Crafted for developers who build exceptional applications. Our Filament Starter delivers tools for faster, better Laravel development.']);
        $this->migrator->add('system_seo.twitter_image', 'sites/twitter-card.png');

        // Schema.org
        $this->migrator->add('system_seo.schema_type', 'SoftwareApplication');
        $this->migrator->add('system_seo.schema_name', 'SuperDuper Filament Starter');
        $this->migrator->add('system_seo.schema_description', ['en' => 'A comprehensive toolkit for Laravel Filament developers featuring pre-configured admin panels, user management, SEO tools, and content management systems.']);
        $this->migrator->add('system_seo.schema_logo', 'sites/structured-data-logo.png');

        // Sitemap
        $this->migrator->add('system_seo.head_additional_meta', '<meta name="author" content="SuperDuper Starter"><meta name="application-name" content="SuperDuper Filament Starter"><link rel="preconnect" href="https://fonts.googleapis.com">');
        $this->migrator->add('system_seo.verification_codes', [
            'google' => '',
            'bing' => '',
            'yandex' => '',
            'baidu' => '',
        ]);
        $this->migrator->add('system_seo.robots_txt_content', "User-agent: *\nAllow: /\n\nSitemap: {site_url}/sitemap.xml");
        $this->migrator->add('system_seo.sitemap_enabled', true);
        $this->migrator->add('system_seo.sitemap_include_pages', true);
        $this->migrator->add('system_seo.sitemap_include_posts', true);
        $this->migrator->add('system_seo.sitemap_include_categories', true);
        $this->migrator->add('system_seo.sitemap_include_tags', true);
    }
};
