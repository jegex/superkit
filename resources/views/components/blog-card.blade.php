@props(['post'])

<article class="group rounded-xl border border-gray-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
    @if ($post->hasFeaturedImage())
        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden rounded-t-xl">
            <img
                src="{{ $post->getFeaturedImageUrl('medium') }}"
                alt="{{ $post->title }}"
                class="aspect-16/9 w-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
        </a>
    @endif

    <div class="p-6">
        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
            <time datetime="{{ $post->published_at->toIso8601String() }}">
                {{ $post->published_at->format('M d, Y') }}
            </time>

            @if ($post->tags->isNotEmpty())
                <div class="flex items-center gap-2">
                    @foreach ($post->tags->take(2) as $tag)
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <h3 class="mt-3">
            <a
                href="{{ route('blog.show', $post->slug) }}"
                class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-primary-600 dark:text-white dark:group-hover:text-primary-400"
            >
                {{ $post->title }}
            </a>
        </h3>

        <p class="mt-2 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
            {{ $post->excerpt }}
        </p>

        <a
            href="{{ route('blog.show', $post->slug) }}"
            class="mt-4 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400"
        >
            {{ __('Read more') }}
            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</article>
