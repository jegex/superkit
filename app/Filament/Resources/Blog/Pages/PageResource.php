<?php

namespace App\Filament\Resources\Blog\Pages;

use App\Enums\ContentType;
use App\Filament\Contracts\Content\ContentResource as BaseContentResource;
use App\Filament\Resources\Blog\Pages\Pages\CreatePage;
use App\Filament\Resources\Blog\Pages\Pages\EditPage;
use App\Filament\Resources\Blog\Pages\Pages\ListPages;
use App\Models\Blog\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PageResource extends BaseContentResource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $type = ContentType::Page;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = -8;

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
