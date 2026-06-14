<x-frontend-layout title="{{ __('Blog') }}">
    <div class="bg-gradient-to-br from-primary-600 to-primary-500 px-6 py-16 text-center text-white">
        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">{{ __('Blog') }}</h1>
        <p class="mt-4 text-lg text-white/80">{{ __('Latest news, tutorials, and updates.') }}</p>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <livewire:frontend.blog-list />
    </div>
</x-frontend-layout>
