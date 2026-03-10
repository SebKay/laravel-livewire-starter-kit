<?php

function renderToastStack(): string
{
    return str_replace(
        ['\u0022', '\u0027', '\u003C', '\u003E', '\u0026'],
        ['"', "'", '<', '>', '&'],
        html_entity_decode(view('components.toast-stack')->render(), ENT_QUOTES),
    );
}

it('binds the toast stack component to the alpine factory', function () {
    $html = renderToastStack();

    expect($html)
        ->toContain('x-data="toastStack({ initialToasts: []')
        ->toContain("closeLabel: '".__('toast.close')."'")
        ->toContain('x-on:toast.window="receive($event.detail)"');
});

it('serializes normalized flashed toasts into the alpine payload', function () {
    session()->flash('toasts', [
        [
            'id' => 'toast-1',
            'message' => 'Saved successfully',
        ],
        [
            'id' => 'toast-2',
            'message' => 'Needs attention',
            'variant' => 'warning',
            'heading' => 'Heads up',
            'duration' => '1500',
            'dismissible' => false,
        ],
        [
            'id' => 'toast-3',
            'message' => 'Fallback duration',
            'variant' => 'invalid',
            'duration' => -50,
        ],
        'invalid-entry',
        [
            'id' => 'toast-4',
            'heading' => 'Missing message',
        ],
    ]);

    $html = renderToastStack();

    expect($html)
        ->toContain('"id":"toast-1"')
        ->toContain('"message":"Saved successfully"')
        ->toContain('"variant":"success"')
        ->toContain('"heading":null')
        ->toContain('"duration":5000')
        ->toContain('"dismissible":true')
        ->toContain('"id":"toast-2"')
        ->toContain('"variant":"warning"')
        ->toContain('"heading":"Heads up"')
        ->toContain('"duration":1500')
        ->toContain('"dismissible":false')
        ->toContain('"id":"toast-3"')
        ->toContain('"message":"Fallback duration"')
        ->toContain('"variant":"success"')
        ->toContain('"duration":0')
        ->not->toContain('invalid-entry')
        ->not->toContain('Missing message');
});
