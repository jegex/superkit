@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $localeUrls = [];
    foreach (LaravelLocalization::getSupportedLocales() as $code => $info) {
        $slug = $post->getTranslation('slug', $code);
        $localeUrls[$code] = LaravelLocalization::getLocalizedURL(
            $code,
            route('blog.show', ['slug' => $slug], false),
        );
    }
@endphp

<x-frontend-layout :title="$post->title" :locale-urls="$localeUrls">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($post->tags->isNotEmpty())
            <div class="mb-6 flex flex-wrap items-center gap-2">
                @foreach ($post->tags as $tag)
                    <span class="rounded-full bg-primary-100 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
            {{ $post->title }}
        </h1>

        <div class="mt-4 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
            <time datetime="{{ $post->published_at->toIso8601String() }}">
                {{ $post->published_at->format('F d, Y') }}
            </time>

            @if ($post->author)
                <span class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ $post->author->name }}
                </span>
            @endif
        </div>

        @if ($post->hasFeaturedImage())
            <div class="mt-8 overflow-hidden rounded-xl">
                <img
                    src="{{ $post->getFeaturedImageUrl('large') }}"
                    alt="{{ $post->title }}"
                    class="aspect-16/9 w-full object-cover"
                >
            </div>
        @endif

        @if ($post->excerpt)
            <p class="mt-8 text-lg leading-relaxed text-gray-600 dark:text-gray-400">
                {{ $post->excerpt }}
            </p>
        @endif

        <div class="prose prose-gray mt-8 max-w-none dark:prose-invert">
            {!! $post->content !!}
        </div>
    </div>
</x-frontend-layout>
