<?php

namespace App\Filament\Resources\Blog\Posts\Pages;

use App\Filament\Contracts\Content\CreateContent as BaseCreateContent;
use App\Filament\Resources\Blog\Posts\PostResource;

class CreatePost extends BaseCreateContent
{
    protected static string $resource = PostResource::class;
}
