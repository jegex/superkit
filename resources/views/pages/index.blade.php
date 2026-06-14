@php
    $latestPosts = \App\Models\Content::published()->byType(\App\Enums\ContentType::Post)->with('tags')->latest('published_at')->take(3)->get();
    $totalPosts = \App\Models\Content::published()->byType(\App\Enums\ContentType::Post)->count();
@endphp

<x-frontend-layout title="{{ __('Home') }}">
    <x-hero
        headline="{{ siteTagline() ?? __('Build Something Amazing') }}"
        tagline="{{ siteDescription() ?? __('A powerful Laravel starter kit with Filament admin panel, multi-language CMS, and more.') }}"
        ctaText="{{ __('Get Started') }}"
        ctaUrl="/admin"
    />

    <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                {{ __('Features') }}
            </h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                {{ __('Everything you need to build modern web applications.') }}
            </p>
        </div>

        <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <x-feature-card
                title="{{ __('Content Management') }}"
                description="{{ __('Manage posts, pages, and products with a powerful CMS built on Filament.') }}"
            />

            <x-feature-card
                title="{{ __('Multi-Language') }}"
                description="{{ __('Built-in translation support for creating multilingual websites.') }}"
            />

            <x-feature-card
                title="{{ __('Role Management') }}"
                description="{{ __('Fine-grained access control with Filament Shield and Spatie permissions.') }}"
            />

            <x-feature-card
                title="{{ __('Media Library') }}"
                description="{{ __('Upload and manage images with Spatie Media Library integration.') }}"
            />

            <x-feature-card
                title="{{ __('Activity Log') }}"
                description="{{ __('Track every change with detailed activity logging and timeline views.') }}"
            />

            <x-feature-card
                title="{{ __('SEO Ready') }}"
                description="{{ __('Built-in meta tags, sitemaps, and SEO-friendly URL structures.') }}"
            />
        </div>
    </div>

    @if ($latestPosts->isNotEmpty())
        <div class="bg-gray-50 dark:bg-gray-950/50">
            <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        {{ __('Latest Posts') }}
                    </h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        {{ __('Stay up to date with our latest articles and tutorials.') }}
                    </p>
                </div>

                <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestPosts as $post)
                        <x-blog-card :post="$post" />
                    @endforeach
                </div>

                @if ($totalPosts > 3)
                    <div class="mt-12 text-center">
                        <a
                            href="{{ route('blog.index') }}"
                            class="inline-flex items-center rounded-lg bg-primary-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition-all hover:bg-primary-500"
                        >
                            {{ __('View All Posts') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-frontend-layout>
