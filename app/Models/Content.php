<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Observers\ContentObserver;
use Filament\Forms\Components\RichEditor\FileAttachmentProviders\SpatieMediaLibraryFileAttachmentProvider;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;
use Relaticle\ActivityLog\Concerns\InteractsWithTimeline;
use Relaticle\ActivityLog\Contracts\HasTimeline;
use Relaticle\ActivityLog\Timeline\TimelineBuilder;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable(['title', 'slug', 'excerpt', 'content'])]
#[Fillable(['title', 'slug', 'excerpt', 'content', 'type', 'status', 'is_featured', 'published_at', 'scheduled_at', 'last_published_at', 'metadata', 'author_id', 'created_by', 'updated_by'])]
#[ObservedBy(ContentObserver::class)]
class Content extends Model implements HasMedia, HasRichContent, HasTimeline, LocalizedUrlRoutable
{
    use HasFactory, SoftDeletes;
    use HasTags;
    use HasTranslations;
    use InteractsWithMedia;
    use InteractsWithRichContent;
    use InteractsWithTimeline;
    use LogsActivity;

    protected $table = 'contents';

    public static function getTagClassName(): string
    {
        return Taxonomy::class;
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(self::getTagClassName(), $this->getTaggableMorphName(), $this->getTaggableTableName(), null, 'tag_id')
            ->using($this->getPivotModelClassName())
            ->ordered();
    }

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'slug' => 'array',
            'excerpt' => 'array',
            'content' => 'array',
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'type' => ContentType::class,
            'status' => ContentStatus::class,
            'is_featured' => 'boolean',
            'last_published_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function setUpRichContent(): void
    {
        $this->registerRichContent('content')
            ->fileAttachmentProvider(
                SpatieMediaLibraryFileAttachmentProvider::make()
                    ->collection('content')
            );
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->format('webp')
            ->quality(90)
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        // Add responsive image sizes - always convert to WebP
        $this->addMediaConversion('thumbnail')
            ->format('webp')
            ->quality(85)
            ->fit(Fit::Contain, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->format('webp')
            ->quality(85)
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->format('webp')
            ->quality(85)
            ->fit(Fit::Contain, 1200, 800)
            ->nonQueued();
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrl(string $conversion = ''): ?string
    {
        $media = $this->getFirstMedia('featured');

        if (! $media) {
            return null;
        }

        return $conversion ? $media->getUrl($conversion) : $media->getUrl();
    }

    /**
     * Check if the post has a featured image
     */
    public function hasFeaturedImage(): bool
    {
        return $this->hasMedia('featured');
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile();

        $this->addMediaCollection('content');
        $this->addMediaCollection('gallery');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    #[Scope]
    protected function scheduled(Builder $query): void
    {
        $query->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now());
    }

    #[Scope]
    protected function draft(Builder $query): void
    {
        $query->whereNull('published_at');
    }

    #[Scope]
    protected function byStatus(Builder $query, ContentStatus $status): void
    {
        $query->where('status', $status->value);
    }

    #[Scope]
    protected function byType(Builder $query, ContentType $type): void
    {
        $query->where('type', $type);
    }

    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function timeline(): TimelineBuilder
    {
        return TimelineBuilder::make($this)->fromActivityLog();
    }

    public function getLocalizedRouteKey($locale)
    {
        return $this->getTranslation('slug', $locale);
    }
}
