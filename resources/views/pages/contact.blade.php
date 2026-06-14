<x-frontend-layout title="{{ __('Contact') }}">
    <div class="bg-gradient-to-br from-primary-600 to-primary-500 px-6 py-16 text-center text-white">
        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">{{ __('Contact') }}</h1>
        <p class="mt-4 text-lg text-white/80">{{ __('Have a question? Send us a message.') }}</p>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <livewire:frontend.contact-form />
            </div>

            @php $company = companyInfo(); @endphp

            @if ($company['name'] ?? null)
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Get in Touch') }}</h2>

                    @if ($company['email'] ?? null)
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Email') }}</p>
                                <a href="mailto:{{ $company['email'] }}" class="text-sm text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
                                    {{ $company['email'] }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($company['phone'] ?? null)
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Phone') }}</p>
                                <a href="tel:{{ $company['phone'] }}" class="text-sm text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
                                    {{ $company['phone'] }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($company['address'] ?? null)
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Address') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $company['address'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-frontend-layout>
