<?php

use App\Models\User;
use Livewire\Livewire;

test('it defaults mobile navigation to closed', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('header')
        ->assertSet('mobileMenuOpen', false);
});

test('it toggles and closes the mobile navigation drawer', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('header')
        ->assertSet('mobileMenuOpen', false)
        ->call('toggleMobileMenu')
        ->assertSet('mobileMenuOpen', true)
        ->call('closeMobileMenu')
        ->assertSet('mobileMenuOpen', false);
});

test('it renders sidebar navigation and utility content', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('header')
        ->assertSee('left-0')
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
