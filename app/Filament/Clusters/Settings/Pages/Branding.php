<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\System\BrandingSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Branding extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static ?int $navigationSort = 2;

    protected static string $settings = BrandingSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Branding Assets')
                    ->description('Upload your brand visual assets.')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->imageEditor()
                            ->directory('branding'),
                        FileUpload::make('logo_dark')
                            ->label('Logo (Dark Mode)')
                            ->image()
                            ->imageEditor()
                            ->directory('branding'),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->imageEditor()
                            ->directory('branding'),
                    ])
                    ->columns(3),
            ]);
    }
}
