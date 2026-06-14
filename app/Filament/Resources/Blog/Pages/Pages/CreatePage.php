<?php

namespace App\Filament\Resources\Blog\Pages\Pages;

use App\Filament\Contracts\Content\CreateContent as BaseCreateContent;
use App\Filament\Resources\Blog\Pages\PageResource;

class CreatePage extends BaseCreateContent
{
    protected static string $resource = PageResource::class;
}
