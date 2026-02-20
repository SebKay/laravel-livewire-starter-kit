@php
    $toasts = collect(session('toasts', []))
        ->filter(fn (mixed $toast): bool => is_array($toast) && filled($toast['message'] ?? null))
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

<div
    x-data="{
        toasts: [],
        timers: {},
        closeLabel: @js(__('toast.Close notification')),
        init() {
            const initialToasts = @js($toasts);

            initialToasts.forEach((toast) => this.push(toast));

            window.addEventListener('toast', (event) => {
                const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;

                this.push(detail);
            });
        },
        push(payload) {
            const toast = this.normalize(payload);

            if (!toast) {
                return;
            }

            this.toasts.push(toast);

            if (this.toasts.length > 3) {
                const oldestToast = this.toasts.shift();

                if (oldestToast?.id) {
                    this.clearTimer(oldestToast.id);
                }
            }

            if (toast.duration > 0) {
                this.timers[toast.id] = setTimeout(() => this.remove(toast.id), toast.duration);
            }
        },
        remove(id) {
            this.clearTimer(id);
            this.toasts = this.toasts.filter((toast) => toast.id !== id);
        },
        clearTimer(id) {
            if (!this.timers[id]) {
                return;
            }

            clearTimeout(this.timers[id]);
            delete this.timers[id];
        },
        normalize(payload) {
            if (!payload || typeof payload !== 'object') {
                return null;
            }

            if (!payload.message) {
                return null;
            }

            const normalizedVariant = ['success', 'warning', 'error'].includes(payload.variant)
                ? payload.variant
                : 'success';

            return {
                id: payload.id || `${Date.now()}-${Math.random()}`,
                message: payload.message,
                heading: payload.heading || null,
                variant: normalizedVariant,
                duration: Number.isInteger(payload.duration) ? Math.max(payload.duration, 0) : 5000,
                dismissible: payload.dismissible ?? true,
            };
        },
    }"
    class="toast-stack"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <article class="toast" :class="`toast-${toast.variant}`" role="status" x-transition.opacity.duration.200ms>
            <div class="flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <h4 class="toast-heading" x-show="toast.heading" x-text="toast.heading"></h4>
                    <p class="toast-message" x-text="toast.message"></p>
                </div>

                <button
                    type="button"
                    class="toast-close"
                    x-show="toast.dismissible"
                    :aria-label="closeLabel"
                    @click="remove(toast.id)"
                >
                    <x-lucide-x class="size-4" />
                </button>
            </div>
        </article>
    </template>
</div>
