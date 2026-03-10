<?php

use App\Models\User;
use Illuminate\Support\Facades\Vite;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('Component', function () {
    it('renders successfully', function () {
        Livewire::actingAs(User::factory()->create())
            ->test('pages::dashboard.index')
            ->assertOk()
            ->assertSee('Dashboard');
    });
});

describe('Users', function () {
    test('Can access the home page', function () {
        actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertOk()
            ->assertSee(__('navigation.nav'))
            ->assertSee(__('navigation.elements'))
            ->assertSee(Vite::asset('resources/js/app.ts'))
            ->assertDontSee('All rights reserved.')
            ->assertDontSee('mobileSidebarListenersRegistered')
            ->assertSee('Seb Kay');
    });
});

describe('Guests', function () {
    test("Can't access the home page", function () {
        get(route('home'))
            ->assertRedirectToRoute('login');
    });
});
