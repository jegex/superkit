<?php

namespace App\Filament\Resources\Blog\Posts\Pages;

use App\Filament\Contracts\Content\EditContent as BaseEditContent;
use App\Filament\Resources\Blog\Posts\PostResource;

class EditPost extends BaseEditContent
{
    protected static string $resource = PostResource::class;
}
