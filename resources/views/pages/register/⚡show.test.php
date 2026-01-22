<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::register.show')
        ->assertStatus(200);
});
