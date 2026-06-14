<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\System\ScriptSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Scripts extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    protected static ?int $navigationSort = 10;

    protected static string $settings = ScriptSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Scripts')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Scripts')
                            ->icon(Heroicon::OutlinedCodeBracketSquare)
                            ->schema([
                                Textarea::make('header_scripts')
                                    ->label('Header Scripts')
                                    ->helperText('Scripts placed in the <head> section.')
                                    ->rows(6)
                                    ->columnSpanFull(),
                                Textarea::make('body_start_scripts')
                                    ->label('Body Start Scripts')
                                    ->helperText('Scripts placed right after the opening <body> tag.')
                                    ->rows(6)
                                    ->columnSpanFull(),
                                Textarea::make('body_end_scripts')
                                    ->label('Body End Scripts')
                                    ->helperText('Scripts placed before the closing </body> tag.')
                                    ->rows(6)
                                    ->columnSpanFull(),
                                Textarea::make('footer_scripts')
                                    ->label('Footer Scripts')
                                    ->helperText('Scripts placed in the footer section.')
                                    ->rows(6)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('Cookie Consent')
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                Toggle::make('cookie_consent_enabled')
                                    ->label('Enable Cookie Consent')
                                    ->helperText('Show a cookie consent banner to visitors.')
                                    ->columnSpanFull(),
                                KeyValue::make('cookie_consent_text')
                                    ->keyLabel('Locale')
                                    ->valueLabel('Consent Text'),
                                KeyValue::make('cookie_consent_button_text')
                                    ->keyLabel('Locale')
                                    ->valueLabel('Button Text'),
                                TextInput::make('cookie_consent_policy_url')
                                    ->label('Privacy Policy URL')
                                    ->url()
                                    ->nullable()
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        Tab::make('Assets')
                            ->icon(Heroicon::OutlinedSwatch)
                            ->schema([
                                Textarea::make('custom_css')
                                    ->label('Custom CSS')
                                    ->helperText('Additional CSS styles applied globally.')
                                    ->rows(10)
                                    ->columnSpanFull(),
                                Textarea::make('custom_js')
                                    ->label('Custom JavaScript')
                                    ->helperText('Additional JavaScript applied globally.')
                                    ->rows(10)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
