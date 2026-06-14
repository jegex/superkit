<x-frontend-layout title="{{ __('About') }}">
    <div class="bg-gradient-to-br from-primary-600 to-primary-500 px-6 py-16 text-center text-white">
        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">{{ __('About') }}</h1>
        <p class="mt-4 text-lg text-white/80">{{ __('Learn more about what we do.') }}</p>
    </div>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        @php
            $page = \App\Models\Content::published()
                ->byType(\App\Enums\ContentType::Page)
                ->where('slug->en', 'about')
                ->first();
        @endphp

        @if ($page)
            @if ($page->hasFeaturedImage())
                <div class="mb-8 aspect-21/9 overflow-hidden rounded-xl">
                    <img src="{{ $page->getFeaturedImageUrl('large') }}" alt="{{ $page->title }}" class="h-full w-full object-cover">
                </div>
            @endif
            <div class="prose prose-gray max-w-none dark:prose-invert">
                {!! $page->content !!}
            </div>
        @else
            <div class="py-16 text-center">
                <p class="text-gray-500">{{ __('About page content has not been created yet.') }}</p>
                <a href="/admin" class="mt-4 inline-block text-sm font-medium text-primary-600 hover:text-primary-500">{{ __('Go to Admin') }}</a>
            </div>
        @endif
    </div>
</x-frontend-layout>
