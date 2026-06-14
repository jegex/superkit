<?php

namespace App\Filament\Resources\Blog\Tags\Pages;

use App\Filament\Contracts\Taxonomy\ManageTaxonomies;
use App\Filament\Resources\Blog\Tags\TagResource;

class ManageTags extends ManageTaxonomies
{
    protected static string $resource = TagResource::class;
}
