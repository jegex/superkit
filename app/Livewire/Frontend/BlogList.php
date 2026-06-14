<?php

namespace App\Livewire\Frontend;

use App\Enums\ContentType;
use App\Enums\TaxonomyType;
use App\Models\Content;
use App\Models\Taxonomy;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BlogList extends Component
{
    use WithPagination;

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $tag = null;

    #[Url]
    public ?string $search = null;

    #[Computed]
    public function posts()
    {
        return Content::published()
            ->byType(ContentType::Post)
            ->when($this->category, fn ($q) => $q->whereHas('tags', fn ($q) => $q->where('slug->en', $this->category)))
            ->when($this->tag, fn ($q) => $q->withAnyTags([$this->tag]))
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->with('tags')
            ->latest('published_at')
            ->paginate(9);
    }

    #[Computed]
    public function categories()
    {
        return Taxonomy::where('type', TaxonomyType::Category)->get();
    }

    public function render(): View
    {
        return view('livewire.frontend.blog-list');
    }
}
