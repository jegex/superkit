<?php

namespace App\Filament\Contracts\Taxonomy;

use App\Filament\Concerns\HasType;
use App\Filament\Schemas\TaxonomyForm;
use App\Filament\Tables\TaxonomiesTable;
use App\Models\Taxonomy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class TaxonomyResource extends Resource
{
    use HasType;
    use Translatable;

    protected static ?string $model = Taxonomy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'name';

    public static bool $hasParent = false;

    public static function form(Schema $schema): Schema
    {
        return TaxonomyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxonomiesTable::configure($table);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
