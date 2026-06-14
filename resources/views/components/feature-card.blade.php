@props(['title', 'description', 'icon' => null])

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
    @if ($icon)
        <div class="inline-flex rounded-lg bg-primary-100 p-3 dark:bg-primary-900/30">
            {{ $icon }}
        </div>
    @endif

    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
        {{ $title }}
    </h3>

    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        {{ $description }}
    </p>
</div>
