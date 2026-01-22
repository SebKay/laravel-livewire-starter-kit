<?php

use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::actingAs(User::factory()->unverified()->create())
        ->test('pages::verification.show')
        ->assertStatus(200);
});
