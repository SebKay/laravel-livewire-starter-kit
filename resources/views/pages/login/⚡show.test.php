<?php

use App\Enums\Environment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;

describe('Component', function () {
    test('Renders login details in local and testing environment', function () {
        Livewire::test('pages::login.show')
            ->assertSet('email', config('seed.users.super.email'))
            ->assertSet('password', config('seed.users.super.password'))
            ->assertSet('remember', true)
            ->assertSet('redirect', '');
    });

    test('Does not render login details in production environment', function () {
        app()->instance('env', Environment::PRODUCTION->value);

        Livewire::test('pages::login.show')
            ->assertSet('email', '')
            ->assertSet('password', '')
            ->assertSet('remember', false)
            ->assertSet('redirect', '');
    });

    test('Can login', function () {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        assertGuest();

        Livewire::test('pages::login.show')
            ->set('email', $user->email)
            ->set('password', $password)
            ->call('login')
            ->assertRedirect(route('home'));

        assertAuthenticated();
    });

    test('Can login and be remembered', function () {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        assertGuest();

        Livewire::test('pages::login.show')
            ->set('email', $user->email)
            ->set('password', $password)
            ->set('remember', true)
            ->call('login')
            ->assertRedirect(route('home'));

        expect($user->fresh()->remember_token)->not->toBeNull();
        assertAuthenticated();
    });

    test('Can be redirected after login', function () {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $redirectUrl = 'https://www.google.com/';

        assertGuest();

        Livewire::test('pages::login.show')
            ->set('email', $user->email)
            ->set('password', $password)
            ->set('redirect', $redirectUrl)
            ->call('login')
            ->assertRedirect($redirectUrl);

        assertAuthenticated();
    });
});

describe('Users', function () {
    test("Can't access the login page", function () {
        actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirectToRoute('home');
    });
});

describe('Guests', function () {
    test('Can access the login page', function () {
        get(route('login'))->assertOk();
    });
});
