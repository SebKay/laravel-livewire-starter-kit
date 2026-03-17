<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('redirects guests to login', function () {
    get(route('password.confirm'))
        ->assertRedirectToRoute('login');
});

it('renders successfully for authenticated users', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('pages::confirm-password.show')
        ->assertOk()
        ->assertSee('Confirm Password');
});

it('confirms the password and redirects to the intended url', function () {
    $user = User::factory()->create([
        'password' => Hash::make('myPassword#123'),
    ]);

    session(['url.intended' => '/intended']);

    Livewire::actingAs($user)
        ->test('pages::confirm-password.show')
        ->set('password', 'myPassword#123')
        ->call('submitPassword')
        ->assertHasNoErrors()
        ->assertRedirect('/intended');

    expect(session('auth.password_confirmed_at'))->not->toBeNull();
});

it('shows a validation error when the password is incorrect', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correctPassword#123'),
    ]);

    Livewire::actingAs($user)
        ->test('pages::confirm-password.show')
        ->set('password', 'wrongPassword#123')
        ->call('submitPassword')
        ->assertHasErrors(['password' => 'current_password'])
        ->assertNoRedirect();
});
