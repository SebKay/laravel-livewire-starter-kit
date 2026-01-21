<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public array $menu = [];

    public bool $mobileMenuOpen = false;

    public function toggleMobileMenu()
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    public function mount()
    {
        $this->menu = [
            [
                'label' => 'Dashboard',
                'route' => route('home'),
                'active' => request()->routeIs('home'),
            ],
            [
                'label' => 'Account',
                'route' => route('account.edit'),
                'active' => request()->routeIs('account.edit'),
            ],
            [
                'label' => 'Logout',
                'route' => route('logout'),
                'active' => false,
            ],
        ];
    }
};
?>

<header class="bg-white border-b border-brand-200 px-4 sm:px-6 xl:px-8">
    <nav>
        <div class="mx-auto max-w-7xl">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="shrink-0 text-brand-800">
                        <x-lucide-sparkles class="size-7" />
                    </a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        @foreach ($menu as $link)
                            <a href="{{ $link['route'] }}" @class([
                                'rounded-xl px-3 py-2 text-sm font-medium cursor-pointer transition-colors',
                                'bg-brand-100 text-brand-950' => $link['active'],
                                'text-brand-600 hover:text-brand-950 focus:text-brand-950' => !$link[
                                    'active'
                                ],
                            ])>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex md:hidden">
                    <button type="button" wire:cloak wire:click="toggleMobileMenu"
                        class="relative inline-flex items-center justify-center rounded-md bg-brand-100 p-2 text-brand-900 hover:bg-brand-900 hover:text-white cursor-pointer">
                        <span class="sr-only">Open main menu</span>
                        <x-lucide-x wire:show="mobileMenuOpen" class="block size-6" />
                        <x-lucide-menu wire:show="!mobileMenuOpen" class="block size-6" />
                    </button>
                </div>
            </div>
        </div>

        <div wire:show="mobileMenuOpen" wire:cloak class="md:hidden">
            <div class="space-y-1 pb-3 pt-2">
                @foreach ($menu as $link)
                    <a href="{{ $link['route'] }}" @class([
                        'rounded-xl px-3 py-2 text-sm font-medium block',
                        'bg-brand-100 text-brand-950' => $link['active'],
                        'text-brand-600 focus:text-brand-950' => !$link['active'],
                    ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
</header>
