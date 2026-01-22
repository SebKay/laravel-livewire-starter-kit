<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('Users', function () {
    test('Can access the verification page', function () {
        actingAs(User::factory()->unverified()->create())
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertSeeLivewire('pages::verification.show');
    });

    test('Can verify their email address', function () {
        $user = User::factory()->unverified()->create();

        expect($user->email_verified_at)->toBeNull();

        actingAs($user)
            ->withoutMiddleware(Illuminate\Routing\Middleware\ValidateSignature::class)
            ->get(route('verification.verify', [
                'id' => $user->getKey(),
                'hash' => sha1((string) $user->getEmailForVerification()),
            ]))
            ->assertRedirectToRoute('home');

        expect($user->refresh()->email_verified_at)->not()->toBeNull();
    });

    test('Can send the verification notice', function () {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        Livewire::actingAs($user)
            ->test('pages::verification.show')
            ->call('resend');

        Notification::assertSentTo($user, Illuminate\Auth\Notifications\VerifyEmail::class);
    });
});

describe('Guests', function () {
    test("Can't access the verification page", function () {
        get(route('verification.notice'))
            ->assertRedirectToRoute('login');
    });
});
