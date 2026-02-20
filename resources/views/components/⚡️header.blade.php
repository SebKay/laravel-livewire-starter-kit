<?php

use Livewire\Component;

new class extends Component {
    public array $menu = [];

    public bool $mobileMenuOpen = false;

    public function mount(): void
    {
        $this->menu = [
            [
                'label' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'route' => route('home'),
                'active' => request()->routeIs('home'),
            ],
            [
                'label' => 'Account',
                'icon' => 'user-circle',
                'route' => route('account.edit'),
                'active' => request()->routeIs('account.edit'),
            ],
        ];
    }

    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    public function closeMobileMenu(): void
    {
        $this->mobileMenuOpen = false;
    }
};
?>

<div wire:keydown.escape.window="closeMobileMenu">
    <button
        type="button"
        wire:click="toggleMobileMenu"
        aria-controls="mobile-sidebar"
        aria-expanded="{{ $mobileMenuOpen ? 'true' : 'false' }}"
        class="fixed left-4 top-4 z-50 inline-flex items-center justify-center rounded-xl border border-brand-200 bg-white p-2 text-brand-900 shadow-sm transition-colors duration-200 hover:bg-brand-50 motion-reduce:transition-none lg:hidden"
    >
        <span class="sr-only">Toggle navigation menu</span>
        <x-lucide-menu wire:show="!mobileMenuOpen" class="size-5" />
        <x-lucide-x wire:show="mobileMenuOpen" class="size-5" />
    </button>

    <aside
        class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-brand-200 lg:bg-white"
        aria-label="Application navigation"
    >
        <div class="flex grow flex-col gap-8 overflow-y-auto px-6 py-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 rounded-xl text-brand-900">
                <x-lucide-sparkles class="size-7 shrink-0" />
                <span class="text-base font-semibold">{{ config('app.name') }}</span>
            </a>

            <nav class="space-y-2">
                @foreach ($menu as $link)
                    <a href="{{ $link['route'] }}" @class([
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                        'bg-brand-100 text-brand-950' => $link['active'],
                        'text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950' => !$link[
                            'active'
                        ],
                    ])>
                        <x-dynamic-component :component="'lucide-' . $link['icon']" class="size-5 shrink-0" />
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto space-y-3 border-t border-brand-200 pt-6">
                <a href="{{ route('account.edit') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-brand-700 transition-colors duration-200 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none">
                    <x-lucide-circle-help class="size-5 shrink-0" />
                    <span>Help</span>
                </a>

                <x-logout-button
                    class="w-full text-left rounded-xl px-3 py-2.5 text-sm font-medium cursor-pointer transition-colors text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950" />

                <p class="px-3 text-xs text-brand-600">
                    {{ config('app.name') }} &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </aside>

    <button
        type="button"
        wire:click="closeMobileMenu"
        @class([
            'fixed inset-0 z-40 bg-brand-950/50 backdrop-blur-[1px] transition-opacity duration-200 motion-reduce:transition-none lg:hidden',
            'opacity-100' => $mobileMenuOpen,
            'pointer-events-none opacity-0' => !$mobileMenuOpen,
        ])
        aria-label="Close navigation menu"
        aria-hidden="{{ $mobileMenuOpen ? 'false' : 'true' }}"
    ></button>

    <aside
        id="mobile-sidebar"
        @class([
            'fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-brand-200 bg-white px-6 py-8 shadow-xl transition-transform duration-200 motion-reduce:transition-none lg:hidden',
            'translate-x-0' => $mobileMenuOpen,
            '-translate-x-full pointer-events-none' => !$mobileMenuOpen,
        ])
        aria-label="Mobile navigation"
        aria-modal="true"
        aria-hidden="{{ $mobileMenuOpen ? 'false' : 'true' }}"
        role="dialog"
    >
        <a href="{{ route('home') }}" wire:click="closeMobileMenu" class="inline-flex items-center gap-3 rounded-xl text-brand-900">
            <x-lucide-sparkles class="size-7 shrink-0" />
            <span class="text-base font-semibold">{{ config('app.name') }}</span>
        </a>

        <nav class="mt-8 space-y-2">
            @foreach ($menu as $link)
                <a href="{{ $link['route'] }}" wire:click="closeMobileMenu" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200 motion-reduce:transition-none',
                    'bg-brand-100 text-brand-950' => $link['active'],
                    'text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950' => !$link[
                        'active'
                    ],
                ])>
                    <x-dynamic-component :component="'lucide-' . $link['icon']" class="size-5 shrink-0" />
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto space-y-3 border-t border-brand-200 pt-6">
            <a href="{{ route('account.edit') }}" wire:click="closeMobileMenu"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-brand-700 transition-colors duration-200 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none">
                <x-lucide-circle-help class="size-5 shrink-0" />
                <span>Help</span>
            </a>

            <x-logout-button
                class="w-full text-left rounded-xl px-3 py-2.5 text-sm font-medium cursor-pointer transition-colors text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none" />

            <p class="px-3 text-xs text-brand-600">
                {{ config('app.name') }} &copy; {{ date('Y') }}
            </p>
        </div>
    </aside>
</div>
