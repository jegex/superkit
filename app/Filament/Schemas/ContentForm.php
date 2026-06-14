<?php

namespace App\Filament\Schemas;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Enums\TaxonomyType;
use App\Filament\Forms\Components\TaxonomySelect;
use App\Filament\Forms\Components\TaxonomyTagInput;
use App\Models\Content;
use App\Services\SlugService;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var Page $livewire */
        $livewire = $schema->getLivewire();
        $resource = $livewire->getResource();
        $type = $resource::getType();

        return $schema
            ->columns(3)
            ->components([
                Group::make([
                    Section::make('Post Content')
                        ->collapsible()
                        ->description('The main content of your blog post')
                        ->icon(Heroicon::OutlinedDocumentText)
                        ->schema([
                            TextInput::make('title')
                                ->maxLength(255)
                                ->autofocus()
                                ->live(onBlur: true)
                                ->partiallyRenderComponentsAfterStateUpdated(['slug'])
                                ->afterStateUpdated(function (Get $get, Set $set, $state, $record) use ($type, $schema) {
                                    if (filled($get('slug'))) {
                                        return;
                                    }

                                    $slug = app(SlugService::class)->generate(
                                        $state,
                                        $schema->getModel(),
                                        $type ?? ContentType::Post->value,
                                        $record,
                                    );

                                    $set('slug', $slug);
                                })
                                ->required(),
                            TextInput::make('slug')
                                ->maxLength(255)
                                ->required()
                                ->belowContent('The "slug" is the URL-friendly version of the name')
                                ->rules([
                                    fn ($record): Closure => function (string $attribute, $value, Closure $fail) use (
                                        $schema,
                                        $type,
                                        $record
                                    ) {
                                        if (app(SlugService::class)->isTaken($value, $schema->getModel(), $type ?? ContentType::Post->value, $record)) {
                                            $fail(__('validation.unique'));
                                        }
                                    },
                                ]),
                            Textarea::make('excerpt')
                                ->placeholder('Provide a brief summary or excerpt of this post')
                                ->rows(5),
                            RichEditor::make('content')
                                ->placeholder('Write your post content here...')
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('blog/posts/content-uploads')
                                ->columnSpanFull()
                                ->maxLength(65535)
                                ->helperText('Format your content using the toolbar above')
                                ->hint(function (Get $get): string {
                                    $wordCount = str_word_count(strip_tags($get('content_raw')));
                                    $readingTime = ceil($wordCount / 200);

                                    return "{$wordCount} words | ~{$readingTime} min read";
                                })
                                ->extraInputAttributes(['style' => 'min-height: 500px;']),
                        ]),

                    Section::make('Media')
                        ->visible($resource::$hasFeaturedImage)
                        ->collapsible()
                        ->description('Visual elements for your post')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('featured')
                                ->disk('public')
                                ->label('Featured Image')
                                ->collection('featured')
                                ->image()
                                ->automaticallyResizeImagesMode('contain')
                                ->automaticallyCropImagesToAspectRatio('16:9')
                                ->automaticallyResizeImagesToWidth('1200')
                                ->automaticallyResizeImagesToHeight('675')
                                ->helperText('This image will be displayed prominently in post listings and social shares (16:9 ratio recommended)')
                                ->downloadable()
                                ->responsiveImages(),
                        ]),

                ])->columnSpan(2),

                Group::make([
                    Section::make('Status & Visibility')
                        ->description('Control how this post appears')
                        ->icon(Heroicon::OutlinedEye)
                        ->schema([
                            Select::make('status')
                                ->options(function (?Content $record) {
                                    $user = auth()->user();
                                    $currentStatus = $record?->status;

                                    $allowedStatuses = [];

                                    if ($user && $user->isSuperAdmin()) {
                                        $allowedStatuses = ContentStatus::class;
                                    } elseif ($user && $user->hasAnyRole(['admin', 'editor'])) {
                                        $allowedStatuses = ContentStatus::class;
                                    } elseif ($user && $user->hasRole('author')) {
                                        $allowedStatuses = [
                                            ContentStatus::DRAFT->value => ContentStatus::DRAFT->getLabel(),
                                            ContentStatus::PENDING->value => ContentStatus::PENDING->getLabel(),
                                        ];

                                        if ($currentStatus === ContentStatus::PUBLISHED) {
                                            $allowedStatuses[ContentStatus::PUBLISHED->value] = ContentStatus::PUBLISHED->getLabel();
                                        }
                                    }

                                    return $allowedStatuses;
                                })
                                ->default(ContentStatus::DRAFT->value)
                                ->live()
                                ->required()
                                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                    if ($state === ContentStatus::PUBLISHED->value && ! $get('published_at')) {
                                        $set('published_at', now(auth()->user()->timezone));
                                    } elseif ($state === ContentStatus::DRAFT->value) {
                                        $set('published_at', null);
                                        $set('scheduled_at', null);
                                    }
                                })
                                ->helperText(function () {
                                    $user = auth()->user();
                                    if ($user && $user->hasRole('author')) {
                                        return 'Authors can create drafts or submit for review. Only editors can publish.';
                                    }

                                    return 'Control the publication status of this post.';
                                }),
                            Select::make('author_id')
                                ->default(auth()->user()->id)
                                ->relationship('author', 'firstname')
                                ->required(),

                            DateTimePicker::make('published_at')
                                ->timezone(auth()->user()->timezone)
                                ->label('Publication Date')
                                ->required(fn (Get $get): bool => $get('status') === ContentStatus::PUBLISHED->value)
                                ->placeholder('Select publication date')
                                ->helperText('Date when the post will be published')
                                ->default(now(auth()->user()->timezone))
                                ->disabled(function () {
                                    $user = auth()->user();

                                    return $user && $user->hasRole('author');
                                }),
                            DateTimePicker::make('scheduled_at')
                                ->timezone(auth()->user()->timezone)
                                ->label('Schedule For')
                                ->required(fn (Get $get): bool => $get('status') === ContentStatus::PENDING->value)
                                ->visible(fn (Get $get): bool => $get('status') === ContentStatus::PENDING->value)
                                ->placeholder('Select scheduled date')
                                ->seconds(false)
                                ->hint('Post will be automatically published at this time')
                                ->hintIcon(Heroicon::OutlinedClock)
                                ->disabled(function (?Content $record) {
                                    $user = auth()->user();

                                    return $user &&
                                        $user->hasRole('author') &&
                                        ! $user->can('schedule', $record ?? new Content);
                                }),
                            Toggle::make('is_featured')
                                ->label('Featured Post')
                                ->helperText('Featured posts appear prominently on the site')
                                ->default(false)
                                ->visible(function (?Content $record) {
                                    $user = auth()->user();

                                    return $user && $user->can('feature', $record ?? new Content);
                                })
                                ->disabled(function (?Content $record) {
                                    $user = auth()->user();

                                    return ! $user || ! $user->can('feature', $record ?? new Content);
                                }),
                        ]),

                    Section::make('Collection')
                        ->description('Organize and classify this post')
                        ->icon(Heroicon::OutlinedTag)
                        ->schema([
                            TaxonomySelect::make('category')
                                ->type(TaxonomyType::Category->value),
                            TaxonomyTagInput::make('tags')
                                ->type(TaxonomyType::Tag->value)
                                ->separator(',')
                                ->tagPrefix('#')
                                ->splitKeys(['Tab', ','])
                                ->trim(),
                        ]),
                    Section::make('SEO')
                        ->description('Search Engine Optimization')
                        ->icon('heroicon-o-magnifying-glass')
                        ->collapsed()
                        ->schema([
                            Textarea::make('meta_title')
                                ->placeholder('Leave empty to use post title')
                                ->maxLength(70)
                                ->helperText('Recommended: 50-60 characters')
                                ->rows(2),
                            Textarea::make('meta_description')
                                ->placeholder('Leave empty to use post overview')
                                ->maxLength(160)
                                ->helperText('Recommended: 150-160 characters')
                                ->rows(5),
                            Section::make()
                                ->schema([
                                    Text::make(function (Get $get): HtmlString {
                                        $title = $get('meta_title') ?: $get('title');
                                        $description = $get('meta_description') ?: $get('excerpt') ?: str($get('content'))->limit(160);
                                        $url = config('app.url').'/blog/'.($get('slug') ?: str($get('title'))->slug());

                                        return new HtmlString("
                                                <div class='text-base font-medium text-primary-600'>{$title}</div>
                                                <div class='text-xs text-gray-500'>{$url}</div>
                                                <div class='mt-2 text-sm text-gray-500'>{$description}</div>
                                            ");
                                    }),
                                ])
                                ->compact(),
                        ]),

                ])->columnSpan(1),
            ]);
    }
}
