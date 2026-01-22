<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component
{
    public string $name = '';
};
?>


<div class="mx-auto max-w-2xl">
    <x-page-title text="Register" />
</div>
