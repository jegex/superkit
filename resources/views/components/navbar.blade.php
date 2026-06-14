@props(['localeUrls' => []])

@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $logo = siteLogo();
    $logoDark = siteLogoDark();
    $hasLogo = $logo || $logoDark;
    $currentLocale = LaravelLocalization::getCurrentLocale();
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    $localeNames = [];
    foreach ($supportedLocales as $code => $info) {
        $localeNames[$code] = $info['native'] ?? $info['name'] ?? $code;
    }

    $localeUrl = function (string $code) use ($localeUrls): string {
        return $localeUrls[$code] ?? LaravelLocalization::getLocalizedURL($code);
    };
@endphp

<nav class="border-b border-gray-200 bg-white/80 backdrop-blur-md dark:border-gray-800 dark:bg-gray-950/80">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('home', [], false)) }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    @if ($hasLogo)
                        @if ($logo)
                            <img src="{{ $logo }}" alt="{{ siteName() }}" class="h-8 w-auto block dark:hidden">
                        @endif
                        @if ($logoDark)
                            <img src="{{ $logoDark }}" alt="{{ siteName() }}" class="h-8 w-auto hidden dark:block">
                        @elseif ($logo)
                            <img src="{{ $logo }}" alt="{{ siteName() }}" class="h-8 w-auto hidden dark:block">
                        @endif
                    @else
                        {{ siteName() }}
                    @endif
                </a>

                <div class="hidden md:flex md:items-center md:gap-6">
                    <a
                        href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('blog.index', [], false)) }}"
                        class="text-sm font-medium transition-colors hover:text-primary-600 {{ request()->routeIs('blog.*') ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                    >
                        {{ __('Blog') }}
                    </a>
                    <a
                        href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('about', [], false)) }}"
                        class="text-sm font-medium transition-colors hover:text-primary-600 {{ request()->routeIs('about') ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                    >
                        {{ __('About') }}
                    </a>
                    <a
                        href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('contact', [], false)) }}"
                        class="text-sm font-medium transition-colors hover:text-primary-600 {{ request()->routeIs('contact') ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                    >
                        {{ __('Contact') }}
                    </a>
                </div>
            </div>

            <div class="hidden items-center gap-4 md:flex">
                @if (count($localeNames) > 1)
                    <div id="lang-toggle" class="relative">
                        <button
                            type="button"
                            onclick="event.stopPropagation(); document.getElementById('lang-dropdown').classList.toggle('hidden');"
                            class="flex items-center gap-1.5 rounded-lg p-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                            aria-label="{{ __('Switch language') }}"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ strtoupper($currentLocale) }}</span>
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            id="lang-dropdown"
                            class="hidden absolute right-0 z-50 mt-1 min-w-[200px] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                        >
                            @foreach ($localeNames as $code => $name)
                                @if ($code !== $currentLocale)
                                    <a
                                        href="{{ $localeUrl($code) }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        {{ $name }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <button
                    type="button"
                    onclick="toggleTheme()"
                    class="cursor-pointer rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                    aria-label="{{ __('Toggle dark mode') }}"
                >
                    <svg class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                @auth
                    <a
                        href="{{ route('filament.admin.pages.dashboard') }}"
                        class="rounded-lg p-2 px-4 text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                    >
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a
                        href="{{ route('filament.admin.auth.login') }}"
                        class="rounded-lg p-2 px-4 text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                    >
                        {{ __('Log in') }}
                    </a>
                @endauth
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 md:hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                aria-label="{{ __('Toggle menu') }}"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden border-t border-gray-200 pb-4 pt-4 dark:border-gray-800">
            <div class="flex flex-col gap-2">
                <a
                    href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('blog.index', [], false)) }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('blog.*') ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                >
                    {{ __('Blog') }}
                </a>
                <a
                    href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('about', [], false)) }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('about') ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                >
                    {{ __('About') }}
                </a>
                <a
                    href="{{ LaravelLocalization::getLocalizedURL($currentLocale, route('contact', [], false)) }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('contact') ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                >
                    {{ __('Contact') }}
                </a>
            </div>
            <div class="mt-4 border-t border-gray-200 pt-4 dark:border-gray-800">
                @if (count($localeNames) > 1)
                    <div class="mb-3 space-y-1">
                        <p class="px-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Language') }}</p>
                        @foreach ($localeNames as $code => $name)
                            <a
                                hreflang="{{ $code }}"
                                href="{{ $localeUrl($code) }}"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 {{ $code === $currentLocale ? 'text-primary-600' : 'text-gray-600 dark:text-gray-400' }}"
                            >
                                {{ $name }}
                                @if ($code === $currentLocale)
                                    <svg class="h-4 w-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-800"></div>
                @endif

                <button
                    type="button"
                    onclick="toggleTheme()"
                    class="mt-3 flex w-full items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                >
                    <svg class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="dark:hidden">{{ __('Dark mode') }}</span>
                    <span class="hidden dark:inline">{{ __('Light mode') }}</span>
                </button>

                @auth
                    <a
                        href="{{ route('filament.admin.pages.dashboard') }}"
                        class="mt-3 flex w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary-500"
                    >
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a
                        href="{{ route('filament.admin.auth.login') }}"
                        class="mt-3 flex w-full rounded-lg px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                    >
                        {{ __('Log in') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
