<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('pages::account.edit')
        ->assertStatus(200);
});

it('updates account details and dispatches a success toast', function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldPassword#123'),
    ]);

    Livewire::actingAs($user)
        ->test('pages::account.edit')
        ->set('name', 'Updated Name')
        ->set('email', 'updated@example.com')
        ->set('password', 'newPassword#123')
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatched('toast', function (string $event, array $params): bool {
            return $event === 'toast'
                && data_get($params, 'variant') === 'success'
                && data_get($params, 'message') === __('account.updated')
                && data_get($params, 'heading') === __('toast.Saved');
        });

    $updatedUser = $user->fresh();

    expect($updatedUser->name)->toBe('Updated Name');
    expect($updatedUser->email)->toBe('updated@example.com');
    expect(Hash::check('newPassword#123', $updatedUser->password))->toBeTrue();
});

it('keeps inline validation and does not dispatch success toast on failure', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('pages::account.edit')
        ->set('name', '')
        ->call('update')
        ->assertHasErrors(['name' => 'required'])
        ->assertNotDispatched('toast');
});
