<?php

use App\Models\User;
use Livewire\Livewire;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('Users', function () {
    test('Can access the edit page', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->get(route('account.edit'))
            ->assertOk()
            ->assertSeeLivewire('pages::account.edit');
    });

    test('Can update their details', function () {
        $user = User::factory()->create([
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
        ]);

        Livewire::actingAs($user)
            ->test('pages::account.edit')
            ->set('name', $newName = fake()->name())
            ->set('email', $newEmail = fake()->safeEmail())
            ->call('update')
            ->assertHasNoErrors();

        expect($user->refresh())
            ->name->toBe($newName)
            ->email->toBe($newEmail);
    });

    test("Can't update their email to one that already exists", function () {
        $user1 = User::factory()->create([
            'email' => fake()->email(),
        ]);

        $user2 = User::factory()->create([
            'email' => $oldEmail = fake()->email(),
        ]);

        Livewire::actingAs($user2)
            ->test('pages::account.edit')
            ->set('email', $user1->email)
            ->call('update')
            ->assertHasErrors(['email']);

        expect($user2->refresh()->email)->toBe($oldEmail);
    });
});

describe('Guests', function () {
    test("Can't access the edit page", function () {
        get(route('account.edit'))
            ->assertRedirectToRoute('login');
    });
});
