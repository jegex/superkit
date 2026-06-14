<?php

namespace App\Filament\Resources\Blog\Posts\Pages;

use App\Filament\Contracts\Content\ListContents as BaseListContents;
use App\Filament\Resources\Blog\Posts\PostResource;

class ListPosts extends BaseListContents
{
    protected static string $resource = PostResource::class;
}
