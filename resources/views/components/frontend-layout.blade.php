@props(['localeUrls' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? siteName() }} — {{ siteName() }}</title>

        @php $seo = seoMeta(); @endphp

        @if ($seo['meta_description'] ?? null)
            <meta name="description" content="{{ $seo['meta_description'] }}">
        @endif

        @if ($seo['meta_keywords'] ?? null)
            <meta name="keywords" content="{{ $seo['meta_keywords'] }}">
        @endif

        @if ($seo['canonical_url'] ?? null)
            <link rel="canonical" href="{{ $seo['canonical_url'] }}">
        @endif

        <meta name="robots" content="{{ ($seo['robots_indexing'] ?? true ? 'index' : 'noindex') }}, {{ ($seo['robots_following'] ?? true ? 'follow' : 'nofollow') }}">

        @if ($seo['og_type'] ?? null)
            <meta property="og:type" content="{{ $seo['og_type'] }}">
        @endif

        @if ($seo['og_title'] ?? null)
            <meta property="og:title" content="{{ $seo['og_title'] }}">
        @endif

        @if ($seo['og_description'] ?? null)
            <meta property="og:description" content="{{ $seo['og_description'] }}">
        @endif

        @if ($seo['og_image'] ?? null)
            <meta property="og:image" content="{{ $seo['og_image'] }}">
        @endif

        @if ($seo['og_site_name'] ?? null)
            <meta property="og:site_name" content="{{ $seo['og_site_name'] }}">
        @endif

        @if ($seo['twitter_card_type'] ?? null)
            <meta name="twitter:card" content="{{ $seo['twitter_card_type'] }}">
        @endif

        @if ($seo['twitter_site'] ?? null)
            <meta name="twitter:site" content="{{ $seo['twitter_site'] }}">
        @endif

        @if ($seo['twitter_creator'] ?? null)
            <meta name="twitter:creator" content="{{ $seo['twitter_creator'] }}">
        @endif

        @if ($seo['twitter_title'] ?? null)
            <meta name="twitter:title" content="{{ $seo['twitter_title'] }}">
        @endif

        @if ($seo['twitter_description'] ?? null)
            <meta name="twitter:description" content="{{ $seo['twitter_description'] }}">
        @endif

        @if ($seo['twitter_image'] ?? null)
            <meta name="twitter:image" content="{{ $seo['twitter_image'] }}">
        @endif

        @if (($seo['verification_codes'] ?? null) && is_array($seo['verification_codes']))
            @foreach ($seo['verification_codes'] as $service => $code)
                @if ($code)
                    @if ($service === 'google')
                        <meta name="google-site-verification" content="{{ $code }}">
                    @elseif ($service === 'bing')
                        <meta name="msvalidate.01" content="{{ $code }}">
                    @elseif ($service === 'yandex')
                        <meta name="yandex-verification" content="{{ $code }}">
                    @elseif ($service === 'baidu')
                        <meta name="baidu-site-verification" content="{{ $code }}">
                    @endif
                @endif
            @endforeach
        @endif

        @if ($seo['head_additional_meta'] ?? null)
            {!! $seo['head_additional_meta'] !!}
        @endif

        @if ($favicon = siteFavicon())
            <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
            <link rel="apple-touch-icon" href="{{ $favicon }}">
        @endif

        @if ($seo['schema_type'] ?? null)
            <script type="application/ld+json">
                {
                    "@@context": "https://schema.org",
                    "@type": "{{ $seo['schema_type'] }}",
                    "name": "{{ $seo['schema_name'] ?? siteName() }}",
                    @if ($seo['schema_description'] ?? null)
                        "description": "{{ $seo['schema_description'] }}",
                    @endif
                    @if ($seo['schema_logo'] ?? null)
                        "logo": "{{ storageUrl($seo['schema_logo']) }}",
                    @endif
                    "url": "{{ url('/') }}"
                }
            </script>
        @endif

        <script>
            if (localStorage.f_theme === 'dark' || (!('f_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }

            function toggleTheme() {
                const isDark = document.documentElement.classList.contains('dark');
                isDark
                    ? document.documentElement.classList.remove('dark')
                    : document.documentElement.classList.add('dark');
                localStorage.f_theme = isDark ? 'light' : 'dark';
            }

            document.addEventListener('click', function (e) {
                var dropdown = document.getElementById('lang-dropdown');
                if (dropdown && !dropdown.classList.contains('hidden') && !e.target.closest('#lang-toggle')) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>

        @php $headerScripts = settingRaw(\App\Settings\System\ScriptSettings::class, 'header_scripts'); @endphp
        @if ($headerScripts)
            {!! $headerScripts !!}
        @endif

        @php $customCss = settingRaw(\App\Settings\System\ScriptSettings::class, 'custom_css'); @endphp
        @if ($customCss)
            <style>
                {!! $customCss !!}
            </style>
        @endif

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-white font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        @php $bodyStartScripts = settingRaw(\App\Settings\System\ScriptSettings::class, 'body_start_scripts'); @endphp
        @if ($bodyStartScripts)
            {!! $bodyStartScripts !!}
        @endif

        <x-navbar :locale-urls="$localeUrls" />

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-footer />

        @php $bodyEndScripts = settingRaw(\App\Settings\System\ScriptSettings::class, 'body_end_scripts'); @endphp
        @if ($bodyEndScripts)
            {!! $bodyEndScripts !!}
        @endif

        @php $footerScripts = settingRaw(\App\Settings\System\ScriptSettings::class, 'footer_scripts'); @endphp
        @if ($footerScripts)
            {!! $footerScripts !!}
        @endif

        @php $customJs = settingRaw(\App\Settings\System\ScriptSettings::class, 'custom_js'); @endphp
        @if ($customJs)
            <script>
                {!! $customJs !!}
            </script>
        @endif
    </body>
</html>
