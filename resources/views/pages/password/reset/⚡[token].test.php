<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

it('resets password and flashes a success toast payload', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    Livewire::withQueryParams(['email' => $user->email])
        ->test('pages::password.reset.[token]', ['token' => $token])
        ->set('password', 'newPassword#123')
        ->set('password_confirmation', 'newPassword#123')
        ->call('resetPassword')
        ->assertHasNoErrors()
        ->assertRedirect(route('login'));

    expect(Hash::check('newPassword#123', $user->fresh()->password))->toBeTrue();
    expect(session('toasts'))->toBeArray();
    expect(session('toasts.0.variant'))->toBe('success');
    expect(session('toasts.0.message'))->toBe(__('passwords.reset'));
});

it('keeps inline validation and does not flash a success toast payload on failure', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    Livewire::withQueryParams(['email' => $user->email])
        ->test('pages::password.reset.[token]', ['token' => $token])
        ->set('password', 'newPassword#123')
        ->set('password_confirmation', 'does-not-match')
        ->call('resetPassword')
        ->assertHasErrors(['password_confirmation' => 'same'])
        ->assertNoRedirect();

    expect(session('toasts'))->toBeNull();
});
