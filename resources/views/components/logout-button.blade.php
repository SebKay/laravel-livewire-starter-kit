<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }} type="submit">
        <x-lucide-log-out class="size-4 shrink-0" />
        <span>{{ __('navigation.Logout') }}</span>
    </button>
</form>
