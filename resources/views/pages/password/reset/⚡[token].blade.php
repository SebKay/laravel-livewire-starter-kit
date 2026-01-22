<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component
{
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    protected function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', PasswordRule::defaults()],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            $this->only('token', 'email', 'password', 'password_confirmation'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            },
        );

        throw_if(
            $status !== Password::PASSWORD_RESET,
            ValidationException::withMessages([
                'email' => __($status),
            ]),
        );

        session()->flash('success', __('passwords.reset'));

        return to_route('login');
    }
};
?>

<div class="mx-auto max-w-2xl">
    <x-page-title text="Reset Password" />

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <form wire:submit="resetPassword">
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
                    <label class="label" for="password">
                        New Password
                    </label>

                    <input id="password" class="input" type="password" name="password" wire:model="password"
                        required />

                    @error('password')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <label class="label" for="password_confirmation">
                        Confirm Password
                    </label>

                    <input id="password_confirmation" class="input" type="password" name="password_confirmation"
                        wire:model="password_confirmation" required />

                    @error('password_confirmation')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <input type="hidden" name="token" wire:model="token" />

                <div class="form-col">
                    <button class="button button-full" wire:submit.prevent="resetPassword">
                        Reset Password
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-6 xl:mt-10">
            <p class="text-center">
                Remember your password?
                <a href="{{ route('login') }}" class="text-link">Log In</a>
            </p>
        </div>
    </div>
</div>
