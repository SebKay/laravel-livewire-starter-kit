<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;

describe('Users', function () {
    test("Can't access the register page", function () {
        actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertRedirectToRoute('home');
    });
});

describe('Guests', function () {
    test('Can access the register page', function () {
        get(route('register'))
            ->assertOk()
            ->assertSeeLivewire('pages::register.show');
    });

    test('Can register', function () {
        Notification::fake();

        $email = fake()->email();

        assertGuest();

        Livewire::test('pages::register.show')
            ->set('name', fake()->name())
            ->set('email', $email)
            ->set('password', 'Pa$$word12345#')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirectToRoute('home');

        assertDatabaseHas('users', [
            'email' => $email,
        ]);

        expect(User::where('email', $email)->firstOrFail()->roles->first()->name)->toBe(Role::USER->value);

        assertAuthenticated();
    });

    test("Can't register with an email that already exists", function () {
        Notification::fake();

        $email = 'jim@test.com';

        User::factory()->create([
            'email' => $email,
        ]);

        Livewire::test('pages::register.show')
            ->set('name', fake()->name())
            ->set('email', $email)
            ->set('password', 'P$ssword12345#')
            ->call('register')
            ->assertHasErrors(['email']);
    });
});
