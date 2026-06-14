<?php

namespace App\Filament\Resources\Blog\Pages\Pages;

use App\Filament\Contracts\Content\EditContent as BaseEditContent;
use App\Filament\Resources\Blog\Pages\PageResource;

class EditPage extends BaseEditContent
{
    protected static string $resource = PageResource::class;
}
