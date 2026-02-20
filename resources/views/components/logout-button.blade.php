<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button {{ $attributes->merge(['class' => '']) }} type="submit">{{ __('navigation.Logout') }}</button>
</form>
