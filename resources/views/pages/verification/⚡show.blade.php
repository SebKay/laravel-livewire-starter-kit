<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component {
    public User $user;

    public function mount(?int $id = null, ?string $hash = null)
    {
        $this->user = auth()->guard()->user();

        if ($id !== null && $hash !== null) {
            $this->verify($id, $hash);
        }
    }

    protected function verify(int $id, string $hash): void
    {
        if ($this->user->id !== $id) {
            abort(403);
        }

        if (!hash_equals(sha1($this->user->getEmailForVerification()), $hash)) {
            abort(403);
        }

        if (!$this->user->hasVerifiedEmail()) {
            $this->user->markEmailAsVerified();

            event(new Verified($this->user));
        }

        $this->redirect(route('home'));
    }

    public function resend(): void
    {
        $key = 'verification-resend:' . $this->user->id;

        if (RateLimiter::tooManyAttempts($key, 6)) {
            $this->addError(
                'resend',
                __('Too many attempts. Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($key),
                ]),
            );

            return;
        }

        RateLimiter::hit($key, 60);

        $this->user->sendEmailVerificationNotification();
    }
};
?>

<div class="mx-auto max-w-2xl">
    <x-page-title text="Verify Your Email" />

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <div class="text-center">
            <p>
                Please verify your email address by clicking on the link we just emailed to you.
            </p>

            <button class="button mt-6" wire:click="resend">
                Resend Verification Email
            </button>
        </div>
    </div>

    <div class="mt-6 xl:mt-10">
        <div class="text-center mt-3">
            <x-logout-button class="text-link" />
        </div>
    </div>
</div>
