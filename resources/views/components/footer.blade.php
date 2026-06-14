@php
    $socials = socialLinks();
    $company = companyInfo();
    $legals = legalLinks();
    $copyright = settingRaw(\App\Settings\System\LegalSettings::class, 'copyright_text');
    $copyrightText = is_array($copyright) ? ($copyright[app()->getLocale()] ?? null) : $copyright;
@endphp

<footer class="border-t border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @if ($company['name'] ?? null)
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $company['name'] }}</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        @if ($company['email'] ?? null)
                            <li>
                                <a href="mailto:{{ $company['email'] }}" class="hover:text-primary-600 transition-colors">{{ $company['email'] }}</a>
                            </li>
                        @endif
                        @if ($company['phone'] ?? null)
                            <li>
                                <a href="tel:{{ $company['phone'] }}" class="hover:text-primary-600 transition-colors">{{ $company['phone'] }}</a>
                            </li>
                        @endif
                        @if ($company['address'] ?? null)
                            <li class="text-balance">{{ $company['address'] }}</li>
                        @endif
                    </ul>
                </div>
            @endif

            @if (! empty($socials))
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Follow Us') }}</h3>
                    <div class="mt-4 flex flex-wrap gap-3">
                        @foreach ($socials as $platform => $url)
                            <a
                                href="{{ $url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 text-sm text-gray-600 transition-colors hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400"
                            >
                                {{ ucfirst($platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (! empty($legals))
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Legal') }}</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        @if ($legals['terms'] ?? null)
                            <li>
                                <a href="{{ $legals['terms'] }}" class="hover:text-primary-600 transition-colors">{{ __('Terms of Service') }}</a>
                            </li>
                        @endif
                        @if ($legals['privacy'] ?? null)
                            <li>
                                <a href="{{ $legals['privacy'] }}" class="hover:text-primary-600 transition-colors">{{ __('Privacy Policy') }}</a>
                            </li>
                        @endif
                        @if ($legals['cookie'] ?? null)
                            <li>
                                <a href="{{ $legals['cookie'] }}" class="hover:text-primary-600 transition-colors">{{ __('Cookie Policy') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>

        <div class="mt-10 border-t border-gray-200 pt-8 dark:border-gray-800">
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                {{ $copyrightText ?? ('&copy; ' . date('Y') . ' ' . siteName() . '. ' . __('All rights reserved.')) }}
            </p>
        </div>
    </div>
</footer>
