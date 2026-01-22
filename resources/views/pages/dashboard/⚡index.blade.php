<?php

use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public string $title = 'Dashboard';

    public User $user;

    public function mount()
    {
        $this->user = auth()->guard()->user();
    }
};
?>

<div>
    <h1 class="xl:text-4xl text-3xl font-medium text-neutral-900 xl:mb-8 mb-4">
        {{ $title }}
    </h1>

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        @if ($user->can(\App\Enums\Permission::CREATE_POSTS))
            <p>User can create posts.</p>
        @else
            <p>User can't create posts.</p>
        @endif
    </div>
</div>
