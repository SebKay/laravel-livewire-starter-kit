<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::account.edit')
        ->assertStatus(200);
});
