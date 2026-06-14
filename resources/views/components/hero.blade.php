@props(['headline', 'tagline' => null, 'ctaText' => null, 'ctaUrl' => '#'])

<div class="border-b border-gray-200 dark:border-gray-800 px-4 py-24 sm:px-6 sm:py-32 lg:px-8">
    <div class="mx-auto max-w-4xl text-center">
        <h1 class="text-4xl font-bold tracking-tight text-gray-950 sm:text-6xl dark:text-gray-100">
            {{ $headline }}
        </h1>

        @if ($tagline)
            <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-800 dark:text-gray-300">
                {{ $tagline }}
            </p>
        @endif

        @if ($ctaText)
            <div class="mt-10">
                <a
                    href="{{ $ctaUrl }}"
                    class="inline-flex items-center rounded-lg bg-white px-6 py-3 text-base font-semibold text-primary-600 shadow-sm transition-all hover:shadow-lg dark:bg-gray-900 dark:text-primary-300 dark:hover:shadow-primary-500/20"
                >
                    {{ $ctaText }}
                </a>
            </div>
        @endif
    </div>
</div>
