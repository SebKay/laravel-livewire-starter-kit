<?php

use Livewire\Component;

new class extends Component {
    public array $menu = [];

    public bool $mobileMenuOpen = false;

    public function mount(): void
    {
        $this->menu = [
            [
                'label' => __('navigation.Dashboard'),
                'icon' => 'layout-dashboard',
                'route' => route('home'),
                'active' => request()->routeIs('home'),
            ],
            [
                'label' => __('navigation.Account'),
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
    <div class="pt-4 lg:pt-8 px-4 sm:px-6 lg:px-8 lg:hidden">
        <button type="button" wire:click="toggleMobileMenu" aria-controls="mobile-sidebar"
            aria-expanded="{{ $mobileMenuOpen ? 'true' : 'false' }}"
            aria-label="{{ __('navigation.Toggle navigation menu') }}"
            class="inline-flex items-center justify-center rounded-xl border border-brand-200 bg-white p-2 text-brand-900 shadow-sm transition-colors duration-200 hover:bg-brand-50 motion-reduce:transition-none">
            <span class="sr-only">{{ __('navigation.Toggle navigation menu') }}</span>
            <x-lucide-menu wire:show="!mobileMenuOpen" class="size-5" />
            <x-lucide-x wire:show="mobileMenuOpen" class="size-5" />
        </button>
    </div>

    <button type="button" wire:click="closeMobileMenu" @class([
        'fixed inset-0 z-40 bg-brand-950/50 backdrop-blur-[1px] transition-opacity duration-200 motion-reduce:transition-none lg:hidden',
        'opacity-100' => $mobileMenuOpen,
        'pointer-events-none opacity-0' => !$mobileMenuOpen,
    ])
        aria-label="{{ __('navigation.Close navigation menu') }}"
        aria-hidden="{{ $mobileMenuOpen ? 'false' : 'true' }}"></button>

    <aside id="mobile-sidebar" @class([
        'fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-brand-200 bg-white px-6 py-8 shadow-xl transition-transform duration-200 motion-reduce:transition-none lg:z-40 lg:translate-x-0 lg:pointer-events-auto lg:shadow-none',
        'translate-x-0' => $mobileMenuOpen,
        '-translate-x-full pointer-events-none' => !$mobileMenuOpen,
    ]) aria-label="{{ __('navigation.Application navigation') }}">
        <a href="{{ route('home') }}" wire:click="closeMobileMenu"
            class="inline-flex items-center gap-2 rounded-xl text-brand-900">
            <x-lucide-sparkles class="size-7 shrink-0" />
            <span class="text-lg font-semibold">App Name</span>
        </a>

        <nav class="mt-8 space-y-2">
            @foreach ($menu as $link)
                <a href="{{ $link['route'] }}" wire:click="closeMobileMenu" @class([
                    'flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200 motion-reduce:transition-none',
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

        <div class="mt-auto">
            <div class="space-y-2 border-t border-brand-200 mt-6 pt-6">
                <a href="{{ route('account.edit') }}" wire:click="closeMobileMenu"
                    class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium text-brand-700 transition-colors duration-200 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none">
                    <x-lucide-circle-help class="size-5 shrink-0" />
                    <span>{{ __('navigation.Help') }}</span>
                </a>

                <x-logout-button
                    class="w-full text-left rounded-xl px-3 py-2.5 text-sm font-medium cursor-pointer transition-colors text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950 motion-reduce:transition-none" />
            </div>

            <p class="mt-3 px-3 text-xs text-brand-600">
                &copy; {{ date('Y') }} <a href="https://sebkay.com/" class="text-link" target="_blank">Seb Kay</a>.
            </p>
        </div>
    </aside>
</div>
