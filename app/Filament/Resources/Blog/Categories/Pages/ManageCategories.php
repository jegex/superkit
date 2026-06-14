<?php

namespace App\Filament\Resources\Blog\Categories\Pages;

use App\Filament\Contracts\Taxonomy\ManageTaxonomies;
use App\Filament\Resources\Blog\Categories\CategoryResource;

class ManageCategories extends ManageTaxonomies
{
    protected static string $resource = CategoryResource::class;
}
