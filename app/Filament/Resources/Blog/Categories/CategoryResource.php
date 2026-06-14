<?php

namespace App\Filament\Resources\Blog\Categories;

use App\Enums\TaxonomyType;
use App\Filament\Contracts\Taxonomy\TaxonomyResource;
use App\Filament\Resources\Blog\Categories\Pages\ManageCategories;
use App\Models\Blog\Category;
use BackedEnum;
use UnitEnum;

class CategoryResource extends TaxonomyResource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $type = TaxonomyType::Category;

    protected static string|null|UnitEnum $navigationGroup = 'Blog';

    public static bool $hasParent = true;

    public static function getPages(): array
    {
        return [
            'index' => ManageCategories::route('/'),
        ];
    }
}
