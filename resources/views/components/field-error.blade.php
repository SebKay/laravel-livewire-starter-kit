@if ($slot->hasActualContent())
    <p class="field-error">
        {{ $slot }}
    </p>
@endif
