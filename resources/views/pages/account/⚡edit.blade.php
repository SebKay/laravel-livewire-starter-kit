<?php

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

new class extends Component
{
    public string $title = 'Update Account';

    public User $user;

    public string $name;

    public string $email;

    public string $password = '';

    public function mount()
    {
        $this->user = auth()->guard()->user();

        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$this->user->id],
            'password' => ['nullable', Password::defaults()],
        ];
    }

    public function update()
    {
        $this->validate();

        $this->user->update($this->only('name', 'email'));
    }
};
?>

<div class="mx-auto max-w-3xl">
    <h1 class="xl:text-4xl text-3xl font-medium text-neutral-900 xl:mb-8 mb-4">
        {{ $title }}
    </h1>

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <form wire:submit="update">
            <div class="form-row">
                <div class="form-col">
                    <label class="label" for="name">
                        Name
                    </label>

                    <input id="name" class="input" type="text" name="name" required wire:model="name" />

                    @error('name')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <label class="label" for="email">
                        Email
                    </label>

                    <input id="email" class="input" type="email" name="email" required wire:model="email" />

                    @error('email')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <label class="label" for="password">
                        Password
                    </label>

                    <input id="password" class="input" type="password" name="password" wire:model="password" />

                    <p class="field-hint">Leave blank to keep current password</p>

                    @error('password')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <button class="button" wire:submit="update">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
