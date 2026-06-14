<x-frontend-layout title="{{ __('Maintenance Mode') }}">
    <div class="flex flex-1 items-center justify-center px-4 py-24">
        <div class="text-center">
            <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30">
                <svg class="h-10 w-10 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25-5.25a1.5 1.5 0 010-2.12l2.12-2.12a1.5 1.5 0 012.12 0l5.25 5.25m-5.25 5.25l2.12 2.12a1.5 1.5 0 002.12 0l2.12-2.12a1.5 1.5 0 000-2.12l-5.25-5.25m5.25 5.25l-5.25 5.25" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ __('Under Maintenance') }}</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                {{ __('We are currently performing scheduled maintenance. We will be back shortly.') }}
            </p>
        </div>
    </div>
</x-frontend-layout>
