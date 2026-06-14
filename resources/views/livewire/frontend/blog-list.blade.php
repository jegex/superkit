<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('blog.index') }}"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition-colors @if (!$category) bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 @else bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 @endif"
            >
                {{ __('All') }}
            </a>

            @foreach ($this->categories as $cat)
                <a
                    href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition-colors @if ($category === $cat->slug) bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 @else bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 @endif"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="relative">
            <svg
                class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="search"
                placeholder="{{ __('Search posts...') }}"
                wire:model.live.debounce.300ms="search"
                class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500"
            >
        </div>
    </div>

    @if ($this->posts->isEmpty())
        <div class="py-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('No posts found') }}</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                @if ($search)
                    {{ __('No posts match your search. Try different keywords.') }}
                @else
                    {{ __('Check back later for new content.') }}
                @endif
            </p>
        </div>
    @else
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->posts as $post)
                <x-blog-card :post="$post" />
            @endforeach
        </div>

        <div class="mt-12">
            {{ $this->posts->links() }}
        </div>
    @endif
</div>
