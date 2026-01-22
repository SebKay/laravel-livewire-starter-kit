<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('The forgot password page can be accessed', function () {
    get(route('password'))
        ->assertOk()
        ->assertSeeLivewire('pages::password.show');
});

test('A password reset email can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test('pages::password.show')
        ->set('email', $user->email)
        ->call('sendResetLink')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('login');

    Notification::assertSentTo($user, ResetPassword::class);
});

test("A password reset email can't be requested with invalid credentials", function () {
    Notification::fake();

    Livewire::test('pages::password.show')
        ->set('email', fake()->email())
        ->call('sendResetLink')
        ->assertHasErrors(['email']);

    Notification::assertNothingSent();
});

test('The password reset page can be accessed', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    get(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]))
        ->assertOk()
        ->assertSeeLivewire('pages::password.reset.[token]');
});

test('Users can reset their passwords', function () {
    $user = User::factory()->create([
        'password' => Hash::make(fake()->password(6)),
    ]);

    $token = Password::createToken($user);
    $newPassword = 'newPassword#1234';

    Livewire::test('pages::password.reset.[token]', ['token' => $token])
        ->set('email', $user->email)
        ->set('password', $newPassword)
        ->set('password_confirmation', $newPassword)
        ->call('resetPassword')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('login');

    expect(Hash::check($newPassword, $user->refresh()->password))->toBeTrue();
});
