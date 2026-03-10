@php
    $toasts = collect(session('toasts', []))
        ->filter(fn(mixed $toast): bool => is_array($toast) && filled($toast['message'] ?? null))
        ->map(function (array $toast): array {
            $duration = isset($toast['duration']) ? (int) $toast['duration'] : 5000;

            return [
                'id' => (string) ($toast['id'] ?? str()->ulid()),
                'message' => (string) $toast['message'],
                'variant' => in_array($toast['variant'] ?? null, ['success', 'warning', 'error'], true)
                    ? $toast['variant']
                    : 'success',
                'heading' => filled($toast['heading'] ?? null) ? (string) $toast['heading'] : null,
                'duration' => max(0, $duration),
                'dismissible' => array_key_exists('dismissible', $toast) ? (bool) $toast['dismissible'] : true,
            ];
        })
        ->values()
        ->all();
@endphp

<div x-data="toastStack({ initialToasts: @js($toasts), closeLabel: @js(__('toast.close')) })"
    x-on:toast.window="receive($event.detail)" class="toast-stack" aria-live="polite">
    <template x-for="toast in toasts" :key="toast.id">
        <article class="toast transition-opacity"
            :class="{
                'toast-success': toast.variant === 'success',
                'toast-warning': toast.variant === 'warning',
                'toast-error': toast.variant === 'error',
            }"
            role="status" x-show="toast.visible" x-transition:enter="transition-opacity"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <h4 class="toast-heading" x-show="toast.heading" x-text="toast.heading"></h4>
                    <p class="toast-message" x-text="toast.message"></p>
                </div>

                <button type="button" class="toast-close" x-show="toast.dismissible" :aria-label="closeLabel"
                    @click="remove(toast.id)">
                    <x-lucide-x class="size-4" />
                </button>
            </div>
        </article>
    </template>
</div>
