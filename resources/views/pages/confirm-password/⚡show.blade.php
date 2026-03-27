<?php

use App\Livewire\Concerns\InteractsWithToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    use InteractsWithToasts;

    public string $password = '';

    protected function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }

    public function submitPassword(): mixed
    {
        $this->validate();

        session()->put('auth.password_confirmed_at', time());

        $this->toastSuccess(
            message: __('Password confirmed.'),
            heading: __('toast.saved'),
        );

        return redirect()->intended(route('home'));
    }
};
?>

<div class="mx-auto max-w-3xl">
    <h1 class="mb-4 text-3xl font-medium text-neutral-900 xl:mb-8 xl:text-4xl">
        Confirm Password
    </h1>

    <div class="rounded-2xl bg-white p-6 xl:p-10">
        <form wire:submit="submitPassword">
            <div class="form-row">
                <div class="form-col">
                    <label class="label" for="password"> Password </label>

                    <input
                        id="password"
                        class="input"
                        type="password"
                        name="password"
                        wire:model="password"
                        required
                        autocomplete="current-password"
                    />

                    @error ('password')
                        <x-field-error>{{ $message }}</x-field-error>
                    @enderror
                </div>

                <div class="form-col">
                    <button class="button button-full" type="submit">
                        Confirm
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-6 xl:mt-10">
            <p class="text-center">For security, please confirm your password to continue.</p>
        </div>
    </div>
</div>
