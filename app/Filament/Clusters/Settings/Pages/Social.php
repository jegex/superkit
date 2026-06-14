<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\System\SocialSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Social extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 9;

    protected static string $settings = SocialSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Social')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Profiles')
                            ->icon(Heroicon::OutlinedUsers)
                            ->schema([
                                TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('facebook'),
                                TextInput::make('twitter_url')
                                    ->label('Twitter / X URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('twitter'),
                                TextInput::make('instagram_url')
                                    ->label('Instagram URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('instagram'),
                                TextInput::make('linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('linkedin'),
                                TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('youtube'),
                                TextInput::make('pinterest_url')
                                    ->label('Pinterest URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('pinterest'),
                                TextInput::make('tiktok_url')
                                    ->label('TikTok URL')
                                    ->url()
                                    ->nullable()
                                    ->prefix('tiktok'),
                            ])
                            ->columns(2),

                        Tab::make('Sharing')
                            ->icon(Heroicon::OutlinedShare)
                            ->schema([
                                Toggle::make('social_share_enabled')
                                    ->label('Enable Social Sharing')
                                    ->helperText('Show social share buttons on your content pages.')
                                    ->columnSpanFull(),
                                TagsInput::make('social_share_platforms')
                                    ->label('Share Platforms')
                                    ->helperText('Add platforms like: facebook, twitter, linkedin, whatsapp, telegram')
                                    ->placeholder('Add a platform'),
                                FileUpload::make('social_share_default_image')
                                    ->label('Default Share Image')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('social'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
