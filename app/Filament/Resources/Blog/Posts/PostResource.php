<?php

namespace App\Filament\Resources\Blog\Posts;

use App\Enums\ContentType;
use App\Filament\Contracts\Content\ContentResource as BaseContentResource;
use App\Filament\Resources\Blog\Posts\Pages\CreatePost;
use App\Filament\Resources\Blog\Posts\Pages\EditPost;
use App\Filament\Resources\Blog\Posts\Pages\ListPosts;
use App\Models\Blog\Post;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PostResource extends BaseContentResource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $type = ContentType::Post;

    public static bool $hasFeaturedImage = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = -9;

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
