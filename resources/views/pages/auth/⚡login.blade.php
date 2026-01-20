<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component {
    //
};
?>

<div class="mx-auto max-w-2xl">
    <livewire:page-title text="Log In" />

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <form>
            <div class="form-row">
                <div class="form-col">
                    <label class="label" for="email">
                        Email
                    </label>
                    <input id="email" class="input" type="email" name="email" required />
                    {{-- <FieldError :message="errors.email" /> --}}
                </div>

                <div class="form-col">
                    <label class="label flex justify-between" for="password">
                        Password
                        <a href="#" class="text-link">Forgot password?</a>
                    </label>
                    <input id="password" class="input" type="password" name="password" required />
                    {{-- <FieldError :message="errors.password" /> --}}
                </div>

                <div class="form-col">
                    <label class="toggle">
                        <input class="sr-only peer" type="checkbox" name="remember" value="1" />
                        <div>
                        </div>
                        <span>
                            Remember me
                        </span>
                    </label>
                </div>

                <input type="hidden" name="redirect" />

                <div class="form-col">
                    <button class="button button-full">
                        Log In
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-6 xl:mt-10">
            <p class="text-center mt-3">
                Don't have an account?
                <a href="#" class="text-link">Register</a>
            </p>
        </div>
    </div>
</div>
