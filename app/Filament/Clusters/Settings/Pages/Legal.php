<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Support\TranslatableField;
use App\Settings\System\LegalSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Legal extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static ?int $navigationSort = 5;

    protected static string $settings = LegalSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Legal Pages')
                    ->description('Links to your legal pages and copyright information.')
                    ->schema([
                        TextInput::make('terms_url')
                            ->label('Terms of Service URL')
                            ->columnSpanFull()
                            ->maxLength(255),
                        TextInput::make('privacy_url')
                            ->label('Privacy Policy URL')
                            ->columnSpanFull()
                            ->maxLength(255),
                        TextInput::make('cookie_policy_url')
                            ->label('Cookie Policy URL')
                            ->columnSpanFull()
                            ->maxLength(255),
                        TranslatableField::wrapIfEnabled(
                            Textarea::make('copyright_text')
                                ->label('Copyright Text')
                                ->columnSpanFull()
                                ->rows(3),
                        ),
                    ])
                    ->columns(2),
            ]);
    }
}
