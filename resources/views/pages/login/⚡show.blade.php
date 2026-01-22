<?php

use App\Enums\Environment;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component {
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $redirect = '';

    public function mount()
    {
        $this->fill(
            app()->environment([Environment::LOCAL->value, Environment::TESTING->value])
                ? [
                    'email' => config('seed.users.super.email'),
                    'password' => config('seed.users.super.password'),
                    'remember' => true,
                    'redirect' => request()->query('redirect', ''),
                ]
                : [],
        );
    }

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', Password::defaults()],
            'remember' => ['nullable'],
            'redirect' => ['nullable', 'string'],
        ];
    }

    public function login()
    {
        $this->validate();

        throw_if(
            !auth()->guard()->attempt($this->only('email', 'password'), $this->remember),
            ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]),
        );

        if ($this->redirect) {
            return redirect($this->redirect);
        }

        return redirect()->intended(route('home'));
    }
};
?>

<div class="mx-auto max-w-2xl">
    <x-page-title text="Log In" />

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <form wire:submit="login">
            <div class="form-row">
                <div class="form-col">
                    <label class="label" for="email">
                        Email
                    </label>

                    <input id="email" class="input" type="email" name="email" wire:model="email" required />

                    @error('email')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <label class="label flex justify-between" for="password">
                        Password
                        <a href="{{ route('password') }}" class="text-link">Forgot password?</a>
                    </label>

                    <input id="password" class="input" type="password" name="password" wire:model="password"
                        required />

                    @error('password')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <label class="toggle">
                        <input class="sr-only peer" type="checkbox" name="remember" value="1"
                            wire:model="remember" />
                        <div>
                        </div>
                        <span>
                            Remember me
                        </span>
                    </label>

                    @error('remember')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <input type="hidden" name="redirect" wire:model="redirect" />

                <div class="form-col">
                    <button class="button button-full" wire:submit.prevent="login">
                        Log In
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-6 xl:mt-10">
            <p class="text-center mt-3">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-link">Register</a>
            </p>
        </div>
    </div>
</div>
