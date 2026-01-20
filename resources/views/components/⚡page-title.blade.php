<?php

use Livewire\Component;

new class extends Component {
    public ?string $text = null;
};
?>
<div>
    @if ($text)
        <h1 class="heading h3 text-center mb-4 xl:mb-8">
            {{ $text }}
        </h1>
    @endif
</div>
