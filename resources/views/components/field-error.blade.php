@if ($slot->hasActualContent())
    <p {{ $attributes->merge(['class' => 'field-error']) }}>{{ $slot }}</p>
@endif
