<?php

namespace App\Models;

use App\Enums\TaxonomyType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Tags\Tag;
use Spatie\Translatable\Attributes\Translatable;

#[Fillable(['name', 'slug', 'description', 'type', 'order_column', 'metadata'])]
#[Translatable(['name', 'slug', 'description'])]
class Taxonomy extends Tag
{
    use NodeTrait;
    use SoftDeletes;

    protected $table = 'taxonomies';

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'slug' => 'array',
            'description' => 'array',
            'metadata' => 'array',
            'type' => TaxonomyType::class,
        ];
    }

    protected function getScopeAttributes(): array
    {
        return ['type'];
    }
}
