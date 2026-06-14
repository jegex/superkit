<?php

namespace App\Filament\Resources\Blog\Pages\Pages;

use App\Filament\Contracts\Content\ListContents as BaseListContents;
use App\Filament\Resources\Blog\Pages\PageResource;

class ListPages extends BaseListContents
{
    protected static string $resource = PageResource::class;
}
