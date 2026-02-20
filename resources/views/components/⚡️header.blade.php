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
};
?>

<aside
    class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-brand-200 lg:bg-white">
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

        <div class="mt-auto border-t border-brand-200 pt-6">
            <x-logout-button
                class="w-full text-left rounded-xl px-3 py-2.5 text-sm font-medium cursor-pointer transition-colors text-brand-700 hover:bg-brand-50 hover:text-brand-950 focus-visible:bg-brand-50 focus-visible:text-brand-950" />
        </div>
    </div>
</aside>
