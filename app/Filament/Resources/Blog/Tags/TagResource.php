<?php

namespace App\Filament\Resources\Blog\Tags;

use App\Enums\TaxonomyType;
use App\Filament\Contracts\Taxonomy\TaxonomyResource;
use App\Filament\Resources\Blog\Tags\Pages\ManageTags;
use App\Models\Blog\Tag;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class TagResource extends TaxonomyResource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|BackedEnum|null $type = TaxonomyType::Tag;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    public static function getPages(): array
    {
        return [
            'index' => ManageTags::route('/'),
        ];
    }
}
