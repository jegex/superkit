<x-frontend-layout title="{{ __('Page Not Found') }}">
    <div class="flex flex-1 items-center justify-center px-4 py-24">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-300 dark:text-gray-700">404</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                {{ settingRaw(\App\Settings\System\ErrorSettings::class, 'custom_404_message')[app()->getLocale()] ?? __('The page you are looking for could not be found.') }}
            </p>
            <a href="{{ route('home') }}" class="mt-8 inline-flex items-center rounded-lg bg-primary-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition-all hover:bg-primary-500">
                {{ __('Go Home') }}
            </a>
        </div>
    </div>
</x-frontend-layout>
