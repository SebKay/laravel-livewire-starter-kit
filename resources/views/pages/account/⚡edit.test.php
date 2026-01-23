<?php

use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::actingAs(User::factory()->create())
        ->test('pages::account.edit')
        ->assertStatus(200);
});
