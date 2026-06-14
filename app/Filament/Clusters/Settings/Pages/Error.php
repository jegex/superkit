<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Support\TranslatableField;
use App\Settings\System\ErrorSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Textarea;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Error extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?int $navigationSort = 6;

    protected static string $settings = ErrorSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Error Pages')
                    ->description('Custom error messages shown to visitors.')
                    ->schema([
                        TranslatableField::wrapIfEnabled(
                            Textarea::make('custom_404_message')
                                ->required()
                                ->columnSpanFull(),
                        ),
                        TranslatableField::wrapIfEnabled(
                            Textarea::make('custom_500_message')
                                ->required()
                                ->columnSpanFull(),
                        ),
                    ])
                    ->columns(2),
            ]);
    }
}
