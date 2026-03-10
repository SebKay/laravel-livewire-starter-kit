<?php

use App\Models\User;
use Livewire\Component;

new class extends Component {
    public string $title = 'Dashboard';

    public User $user;

    public function mount()
    {
        $this->user = auth()->guard()->user();
    }
};
?>

<div>
    <h1 class="mb-4 text-3xl font-medium text-neutral-900 xl:mb-8 xl:text-4xl">
        {{ $title }}
    </h1>

    <div class="rounded-2xl bg-white p-6 xl:p-10">Dashboard</div>
</div>
