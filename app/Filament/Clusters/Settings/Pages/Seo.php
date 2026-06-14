<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Support\TranslatableField;
use App\Settings\System\SeoSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Seo extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?int $navigationSort = 8;

    protected static string $settings = SeoSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Seo')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('General')
                            ->icon(Heroicon::OutlinedGlobeAlt)
                            ->schema([
                                TextInput::make('meta_title_format')
                                    ->label('Meta Title Format')
                                    ->helperText('Use {page_title}, {separator}, {site_name} as placeholders.')
                                    ->columnSpanFull(),
                                TranslatableField::wrapIfEnabled(
                                    Textarea::make('meta_description')
                                        ->label('Meta Description')
                                        ->rows(3),
                                ),
                                TranslatableField::wrapIfEnabled(
                                    TagsInput::make('meta_keywords')
                                        ->label('Meta Keywords'),
                                ),
                                TextInput::make('canonical_url')
                                    ->label('Canonical URL')
                                    ->url()
                                    ->nullable(),
                            ])
                            ->columns(2),

                        Tab::make('Robots')
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema([
                                Toggle::make('robots_indexing')
                                    ->label('Allow Indexing')
                                    ->helperText('Allow search engines to index this site.')
                                    ->columnSpanFull(),
                                Toggle::make('robots_following')
                                    ->label('Allow Following Links')
                                    ->helperText('Allow search engines to follow links on this site.')
                                    ->columnSpanFull(),
                                TextInput::make('title_separator')
                                    ->label('Title Separator')
                                    ->maxLength(10),
                                TextInput::make('blog_title_format')
                                    ->label('Blog Title Format')
                                    ->helperText('Use {post_title}, {separator}, {site_name} as placeholders.'),
                                TextInput::make('product_title_format')
                                    ->label('Product Title Format')
                                    ->helperText('Use {product_name}, {separator}, {site_name} as placeholders.'),
                                TextInput::make('category_title_format')
                                    ->label('Category Title Format')
                                    ->helperText('Use {category_name}, {separator}, {site_name} as placeholders.'),
                                TextInput::make('search_title_format')
                                    ->label('Search Title Format')
                                    ->helperText('Use {search_term}, {separator}, {site_name} as placeholders.'),
                                TextInput::make('author_title_format')
                                    ->label('Author Title Format')
                                    ->helperText('Use {author_name}, {separator}, {site_name} as placeholders.'),
                            ])
                            ->columns(2),

                        Tab::make('Open Graph')
                            ->icon(Heroicon::OutlinedShare)
                            ->schema([
                                Select::make('og_type')
                                    ->label('OG Type')
                                    ->options([
                                        'website' => 'Website',
                                        'article' => 'Article',
                                        'profile' => 'Profile',
                                        'video.movie' => 'Movie',
                                        'video.episode' => 'Episode',
                                        'video.tv_show' => 'TV Show',
                                        'music.song' => 'Song',
                                        'music.album' => 'Album',
                                    ])
                                    ->nullable(),
                                TranslatableField::wrapIfEnabled(
                                    TextInput::make('og_title')
                                        ->label('OG Title')
                                        ->maxLength(255),
                                ),
                                TranslatableField::wrapIfEnabled(
                                    Textarea::make('og_description')
                                        ->label('OG Description')
                                        ->rows(3),
                                ),
                                FileUpload::make('og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('seo'),
                                TranslatableField::wrapIfEnabled(
                                    TextInput::make('og_site_name')
                                        ->label('Site Name')
                                        ->maxLength(255),
                                ),
                            ])
                            ->columns(2),

                        Tab::make('Twitter')
                            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                            ->schema([
                                Select::make('twitter_card_type')
                                    ->label('Card Type')
                                    ->options([
                                        'summary' => 'Summary Card',
                                        'summary_large_image' => 'Summary Card with Large Image',
                                        'app' => 'App Card',
                                        'player' => 'Player Card',
                                    ])
                                    ->nullable(),
                                TextInput::make('twitter_site')
                                    ->label('Twitter Site (@username)')
                                    ->prefix('@')
                                    ->nullable()
                                    ->maxLength(255),
                                TextInput::make('twitter_creator')
                                    ->label('Twitter Creator (@username)')
                                    ->prefix('@')
                                    ->nullable()
                                    ->maxLength(255),
                                TranslatableField::wrapIfEnabled(
                                    TextInput::make('twitter_title')
                                        ->label('Twitter Title')
                                        ->maxLength(255),
                                ),
                                TranslatableField::wrapIfEnabled(
                                    Textarea::make('twitter_description')
                                        ->label('Twitter Description')
                                        ->rows(3),
                                ),
                                FileUpload::make('twitter_image')
                                    ->label('Twitter Image')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('seo'),
                            ])
                            ->columns(2),

                        Tab::make('Schema')
                            ->icon(Heroicon::OutlinedCodeBracket)
                            ->schema([
                                TextInput::make('schema_type')
                                    ->label('Schema Type')
                                    ->helperText('e.g., SoftwareApplication, Organization, WebSite')
                                    ->nullable()
                                    ->maxLength(255),
                                TextInput::make('schema_name')
                                    ->label('Schema Name')
                                    ->nullable()
                                    ->maxLength(255),
                                TranslatableField::wrapIfEnabled(
                                    Textarea::make('schema_description')
                                        ->label('Schema Description')
                                        ->rows(3),
                                ),
                                FileUpload::make('schema_logo')
                                    ->label('Schema Logo')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('seo'),
                            ])
                            ->columns(2),

                        Tab::make('Sitemap')
                            ->icon(Heroicon::OutlinedRectangleGroup)
                            ->schema([
                                Toggle::make('sitemap_enabled')
                                    ->label('Enable Sitemap')
                                    ->helperText('Generate an XML sitemap for search engines.')
                                    ->columnSpanFull(),
                                Toggle::make('sitemap_include_pages')
                                    ->label('Include Pages'),
                                Toggle::make('sitemap_include_posts')
                                    ->label('Include Posts'),
                                Toggle::make('sitemap_include_categories')
                                    ->label('Include Categories'),
                                Toggle::make('sitemap_include_tags')
                                    ->label('Include Tags'),
                                KeyValue::make('verification_codes')
                                    ->keyLabel('Search Engine')
                                    ->valueLabel('Verification Code')
                                    ->columnSpanFull(),
                                Textarea::make('robots_txt_content')
                                    ->label('Robots.txt Content')
                                    ->rows(6)
                                    ->columnSpanFull(),
                                Textarea::make('head_additional_meta')
                                    ->label('Additional Head Meta')
                                    ->helperText('Add custom meta tags to the <head> section.')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
