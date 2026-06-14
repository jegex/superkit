<?php

namespace App\Filament\Contracts\Content;

use App\Filament\Concerns\HasType;
use App\Filament\Schemas\ContentForm;
use App\Filament\Tables\ContentsTable;
use App\Models\Content;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ContentResource extends Resource
{
    use HasType;
    use Translatable;

    protected static ?string $model = Content::class;

    public static bool $hasFeaturedImage = false;

    public static function form(Schema $schema): Schema
    {
        return ContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('author')
            ->where('type', static::getType());
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
