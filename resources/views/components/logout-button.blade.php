<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button {{ $attributes->merge(['class' => '']) }} type="submit">Logout</button>
</form>
