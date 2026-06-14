<?php

namespace App\Filament\Forms\Components;

use App\Models\Taxonomy;
use Closure;
use Filament\Forms\Components\Select;
use Filament\SpatieLaravelTagsPlugin\Types\AllTagTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TaxonomySelect extends Select
{
    protected string|Closure|AllTagTypes|null $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->type(new AllTagTypes);

        $this->searchable();

        $this->options(fn (): array => $this->getNestedOptions());

        $this->getSearchResultsUsing(fn (string $search): array => $this->getFilteredOptions($search));

        $this->saveRelationshipsUsing(function (TaxonomySelect $component, ?Model $record, mixed $state): void {
            if (! (method_exists($record, 'syncTagsWithType') && method_exists($record, 'syncTags'))) {
                return;
            }

            $tagIds = array_filter(
                [$state],
                fn (mixed $id): bool => is_numeric($id) && $id > 0,
            );

            $record->tags()->sync($tagIds);
        });

        $this->loadStateFromRelationshipsUsing(function (TaxonomySelect $component, Model $record): void {
            if (! method_exists($record, 'tagsWithType')) {
                return;
            }

            $type = $component->getType();
            $record->load('tags');
            $tags = $record->tags->where('type', $type);

            $component->state($tags->pluck('id')->first());
        });

        $this->dehydrated(false);
    }

    public function type(string|Closure|AllTagTypes|null $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string|AllTagTypes|null
    {
        return $this->evaluate($this->type);
    }

    public function isAnyTagTypeAllowed(): bool
    {
        return $this->getType() instanceof AllTagTypes;
    }

    protected function getTagQuery(): Builder
    {
        $tagClass = config('tags.tag_model', Taxonomy::class);

        /** @var Builder $query */
        $query = $tagClass::query();

        if (! $this->isAnyTagTypeAllowed()) {
            $query->when(
                filled($type = $this->getType()),
                fn (Builder $q) => $q->where('type', $type),
                fn (Builder $q) => $q->where('type', null),
            );
        }

        return $query;
    }

    protected function getNestedOptions(): array
    {
        return $this->getTagQuery()
            ->withDepth()
            ->get()
            ->mapWithKeys(fn (Taxonomy $tag): array => [
                $tag->getKey() => str_repeat('— ', max(0, $tag->depth)).' '.$tag->name,
            ])
            ->all();
    }

    protected function getFilteredOptions(string $search): array
    {
        return $this->getTagQuery()
            ->where('name', 'like', "%{$search}%")
            ->pluck('name', 'id')
            ->all();
    }
}
