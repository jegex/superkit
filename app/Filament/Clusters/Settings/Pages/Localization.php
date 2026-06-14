<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\MultiLanguageScope;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Locale;
use App\Settings\System\LocalizationSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Localization extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeEuropeAfrica;

    protected static ?int $navigationSort = 4;

    protected static string $settings = LocalizationSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Default Language')
                    ->description('Language settings.')
                    ->schema([
                        Select::make('default_language')
                            ->label('Default Language')
                            ->options(fn () => Locale::pluck('native', 'code'))
                            ->searchable()
                            ->required(),
                    ]),
                Section::make('Multi Language')
                    ->description('Enable multi-language support for your application.')
                    ->schema([
                        Toggle::make('multi_language_enabled')
                            ->label('Enable Multi Language')
                            ->live(),
                        Select::make('supported_locales')
                            ->multiple()
                            ->options(fn () => Locale::pluck('native', 'code'))
                            ->label('Supported Locales')
                            ->placeholder('Add locale code')
                            ->visible(fn ($get) => $get('multi_language_enabled'))
                            ->required(fn ($get) => $get('multi_language_enabled')),
                        Select::make('multi_language_scope')
                            ->label('Scope')
                            ->options(MultiLanguageScope::class)
                            ->visible(fn ($get) => $get('multi_language_enabled'))
                            ->required(fn ($get) => $get('multi_language_enabled')),
                        Toggle::make('hide_default_locale_in_url')
                            ->label('Hide Default Locale in URL')
                            ->visible(fn ($get) => $get('multi_language_enabled')),
                        Toggle::make('auto_detect_language')
                            ->label('Auto Detect Language')
                            ->visible(fn ($get) => $get('multi_language_enabled')),
                    ]),
            ]);
    }
}
