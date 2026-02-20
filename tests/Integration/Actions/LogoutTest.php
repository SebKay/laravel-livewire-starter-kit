<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

it('logs out the user, invalidates session data, and redirects to login', function () {
    $user = User::factory()->create();
    $previousToken = csrf_token();

    actingAs($user)
        ->withSession(['coverage_marker' => 'present'])
        ->post(route('logout'))
        ->assertRedirectToRoute('login')
        ->assertSessionMissing('coverage_marker');

    assertGuest();
    expect(csrf_token())->not->toBe($previousToken);
});
