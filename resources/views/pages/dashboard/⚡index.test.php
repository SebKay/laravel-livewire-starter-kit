<?php

use App\Models\User;
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
            ->assertOk();
    });
});

describe('Guests', function () {
    test("Can't access the home page", function () {
        get(route('home'))
            ->assertRedirectToRoute('login');
    });
});
