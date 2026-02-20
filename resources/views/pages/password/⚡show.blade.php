<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Livewire\Concerns\InteractsWithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component {
    use InteractsWithToasts;

    public string $email = '';

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users'],
        ];
    }

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink($this->only('email'));

        throw_if(
            $status !== Password::RESET_LINK_SENT,
            ValidationException::withMessages([
                'email' => __($status),
            ]),
        );

        session()->regenerate();
        $this->flashToastSuccess(__('passwords.sent'));

        return to_route('login');
    }
};
?>

<div class="mx-auto max-w-2xl">
    <x-page-title text="Forgot Password" />

    <div class="bg-white rounded-2xl xl:p-10 p-6">
        <form wire:submit="sendResetLink">
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
                    <button class="button button-full" wire:submit.prevent="sendResetLink">
                        Send Reset Link
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
