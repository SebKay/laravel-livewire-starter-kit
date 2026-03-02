<?php

use App\Models\User;
use Livewire\Livewire;

test('it uses javascript actions for the mobile navigation drawer', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('sidebar')
        ->assertSee('wire:click="$js.toggleMobileMenu"', false)
        ->assertSee('wire:click="$js.closeMobileMenu"', false)
        ->assertSee('wire:keydown.escape.window="$js.closeMobileMenu"', false)
        ->assertSee('wire:click="$js.closeMobileMenu" aria-label="Close navigation menu"', false)
        ->assertDontSee('x-bind:class="{ \'hidden\': mobileMenuOpen }"', false)
        ->assertDontSee('x-bind:class="{ \'hidden\': !mobileMenuOpen }"', false);
});

test('it renders sidebar navigation and utility content', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('sidebar')
        ->assertSee('left-0')
        ->assertSee('-translate-x-full')
        ->assertSee('pointer-events-none opacity-0')
        ->assertSee('lg:hidden')
        ->assertSee('overflow-y-auto')
        ->assertDontSee('space-y-3')
        ->assertDontSee('fixed left-4 top-4')
        ->assertDontSee(__('navigation.Mobile navigation'))
        ->assertSee(__('navigation.Dashboard'))
        ->assertSee(__('navigation.Account'))
        ->assertSee(__('navigation.Help'))
        ->assertSee(__('navigation.Logout'));
});
