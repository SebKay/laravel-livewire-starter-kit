<?php

namespace App\Livewire\Actions;

class Logout
{
    public function __invoke()
    {
        auth()->guard()->logout();

        session()->regenerateToken();
        session()->invalidate();

        return to_route('login');
    }
}
