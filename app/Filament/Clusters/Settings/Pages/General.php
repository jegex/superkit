<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Support\TranslatableField;
use App\Settings\System\GeneralSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class General extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static ?int $navigationSort = 1;

    protected static string $settings = GeneralSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Site Identity')
                    ->description('Basic information about your website.')
                    ->schema([
                        TranslatableField::wrapIfEnabled(
                            TextInput::make('name')
                                ->label('Site Name')
                                ->required()
                                ->columnSpanFull()
                                ->maxLength(255),
                        ),
                        TranslatableField::wrapIfEnabled(
                            TextInput::make('tagline')
                                ->label('Tagline')
                                ->columnSpanFull()
                                ->maxLength(255),
                        ),
                        TranslatableField::wrapIfEnabled(
                            Textarea::make('description')
                                ->label('Description')
                                ->columnSpanFull()
                                ->rows(3),
                        ),
                        Toggle::make('is_maintenance')
                            ->label('Maintenance Mode')
                            ->helperText('When enabled, visitors will see a maintenance page.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
