<x-frontend-layout title="{{ __('Server Error') }}">
    <div class="flex flex-1 items-center justify-center px-4 py-24">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-300 dark:text-gray-700">500</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                {{ settingRaw(\App\Settings\System\ErrorSettings::class, 'custom_500_message')[app()->getLocale()] ?? __('Something went wrong on our end. Please try again later.') }}
            </p>
            <a href="{{ route('home') }}" class="mt-8 inline-flex items-center rounded-lg bg-primary-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition-all hover:bg-primary-500">
                {{ __('Go Home') }}
            </a>
        </div>
    </div>
</x-frontend-layout>
