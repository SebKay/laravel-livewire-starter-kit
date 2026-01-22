<?php

use App\Enums\Environment;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function mount()
    {
        $this->fill(
            app()->environment([Environment::LOCAL->value, Environment::TESTING->value])
                ? [
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => '123456',
                ]
                : [],
        );
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ];
    }

    public function register()
    {
        $this->validate();

        $user = new User($this->only('name', 'email'));

        $user->password = Hash::make($this->password);
        $user->save();

        $user->assignRole(Role::USER->value);

        auth()->guard()->loginUsingId($user->id);

        event(new Registered($user));

        return to_route('home');
    }
};
?>

<div class="mx-auto max-w-2xl">
    <x-page-title text="Register" />

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <form wire:submit="register">
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

                    <input id="password" class="input" type="password" name="password" required
                        wire:model="password" />

                    @error('password')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <button class="button button-full" wire:submit.prevent="register">
                        Register
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-6 xl:mt-10">
            <p class="text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-link">Log In</a>
            </p>
        </div>
    </div>
</div>
