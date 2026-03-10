@php
    $menu = [
        [
            'label' => __('navigation.dashboard'),
            'icon' => 'layout-dashboard',
            'route' => route('home'),
            'active' => request()->routeIs('home'),
        ],
        [
            'label' => __('navigation.account'),
            'icon' => 'user-circle',
            'route' => route('account.edit'),
            'active' => request()->routeIs('account.edit'),
        ],
    ];
@endphp

<div>
    <div class="px-4 pt-4 sm:px-6 lg:hidden lg:px-8 lg:pt-8">
        <button
            type="button"
            data-sidebar-toggle
            aria-controls="mobile-sidebar"
            aria-expanded="false"
            aria-label="{{ __('navigation.toggle') }}"
            class="inline-flex items-center justify-center rounded-xl border border-brand-200 bg-white p-2 text-brand-900 shadow-sm transition-colors duration-200 hover:bg-brand-50 motion-reduce:transition-none"
        >
            <span class="sr-only">{{ __('navigation.toggle') }}</span>
            <x-lucide-menu class="size-5" />
        </button>
    </div>

    <button
        type="button"
        data-sidebar-wash
        data-sidebar-close
        tabindex="-1"
        aria-label="{{ __('navigation.close') }}"
        aria-hidden="true"
        class="pointer-events-none fixed inset-0 z-40 bg-brand-950/30 opacity-0 transition-opacity duration-200 motion-reduce:transition-none lg:hidden"
    ></button>

    <aside
        id="mobile-sidebar"
        class="pointer-events-none fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-y-auto overscroll-contain border-r border-brand-200 bg-white px-6 py-8 shadow-xl transition-transform duration-200 motion-reduce:transition-none lg:pointer-events-auto lg:z-40 lg:translate-x-0 lg:shadow-none"
        aria-label="{{ __('navigation.nav') }}"
    >
        <div class="flex items-center justify-between gap-3">
            <a
                href="{{ route('home') }}"
                data-sidebar-close
                class="inline-flex items-center gap-2 rounded-xl text-brand-900"
            >
                <x-lucide-sparkles class="size-6 shrink-0 sm:size-7" />
                <span class="text-lg font-semibold">App Name</span>
            </a>

            <button
                type="button"
                data-sidebar-close
                aria-label="{{ __('navigation.close') }}"
                class="inline-flex items-center justify-center rounded-xl border border-brand-200 bg-white p-2 text-brand-900 shadow-sm transition-colors duration-200 hover:bg-brand-50 motion-reduce:transition-none lg:hidden"
            >
                <span class="sr-only">{{ __('navigation.close') }}</span>
                <x-lucide-x class="size-5" />
            </button>
        </div>

        <nav class="mt-8 space-y-2">
            @foreach ($menu as $link)
                <a
                    href="{{ $link['route'] }}"
                    data-sidebar-close
                    @class ([
                    'flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200 motion-reduce:transition-none',
                    'bg-brand-100 text-brand-950' => $link['active'],
                    'text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950' => !$link[
                        'active'
                    ],
                ])
                >
                    <x-dynamic-component
                        :component="'lucide-' . $link['icon']"
                        class="size-5 shrink-0"
                    />
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto">
            <div class="mt-6 space-y-2 border-t border-brand-200 pt-6">
                <a
                    href="{{ route('elements') }}"
                    data-sidebar-close
                    class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium text-brand-700 transition-colors duration-200 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none"
                >
                    <x-lucide-circle-help class="size-5 shrink-0" />
                    <span>{{ __('navigation.elements') }}</span>
                </a>

                <x-logout-button
                    data-sidebar-close
                    class="w-full cursor-pointer rounded-xl px-3 py-2.5 text-left text-sm font-medium text-brand-700 transition-colors hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none"
                />
            </div>

            <p class="mt-3 px-3 text-xs text-brand-600">&copy; {{ date('Y') }} <a href="https://sebkay.com/" class="text-link" target="_blank">Seb Kay</a>.</p>
        </div>
    </aside>
</div>
