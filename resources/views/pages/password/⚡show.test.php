<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

it('sends reset link and flashes a success toast payload', function () {
    $user = User::factory()->create();

    Notification::fake();

    Livewire::test('pages::password.show')
        ->set('email', $user->email)
        ->call('sendResetLink')
        ->assertHasNoErrors()
        ->assertRedirect(route('login'));

    expect(session('toasts'))->toBeArray();
    expect(session('toasts.0.variant'))->toBe('success');
    expect(session('toasts.0.message'))->toBe(__('passwords.sent'));
});

it('keeps inline validation and does not flash a success toast payload on failure', function () {
    Livewire::test('pages::password.show')
        ->set('email', 'missing@example.com')
        ->call('sendResetLink')
        ->assertHasErrors(['email' => 'exists'])
        ->assertNoRedirect();

    expect(session('toasts'))->toBeNull();
});
