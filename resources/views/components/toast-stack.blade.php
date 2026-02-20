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
        leaveTimers: {},
        toastListener: null,
        transitionDurationMs: 200,
        closeLabel: @js(__('toast.Close notification')),
        init() {
            const initialToasts = @js($toasts);

            this.transitionDurationMs = this.resolveTransitionDurationMs();

            initialToasts.forEach((toast) => this.push(toast));

            this.toastListener = (event) => {
                const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;

                this.push(detail);
            };

            window.addEventListener('toast', this.toastListener);
        },
        destroy() {
            if (this.toastListener) {
                window.removeEventListener('toast', this.toastListener);
            }

            Object.keys(this.timers).forEach((id) => this.clearTimer(id));
            Object.keys(this.leaveTimers).forEach((id) => this.clearLeaveTimer(id));
        },
        push(payload) {
            const toast = this.normalize(payload);

            if (!toast) {
                return;
            }

            this.toasts.push(toast);
            this.$nextTick(() => {
                const queuedToast = this.toasts.find((candidate) => candidate.id === toast.id);

                if (queuedToast) {
                    queuedToast.visible = true;
                }
            });

            if (this.toasts.length > 3) {
                const oldestToast = this.toasts.find((candidate) => candidate.id !== toast.id);

                if (oldestToast?.id) {
                    this.remove(oldestToast.id);
                }
            }

            if (toast.duration > 0) {
                this.timers[toast.id] = setTimeout(() => this.remove(toast.id), toast.duration);
            }
        },
        remove(id) {
            this.clearTimer(id);

            const toast = this.toasts.find((candidate) => candidate.id === id);

            if (!toast || !toast.visible) {
                return;
            }

            toast.visible = false;
            this.clearLeaveTimer(id);
            this.leaveTimers[id] = setTimeout(() => {
                this.toasts = this.toasts.filter((candidate) => candidate.id !== id);
                this.clearLeaveTimer(id);
            }, this.transitionDurationMs);
        },
        clearTimer(id) {
            if (!this.timers[id]) {
                return;
            }

            clearTimeout(this.timers[id]);
            delete this.timers[id];
        },
        clearLeaveTimer(id) {
            if (!this.leaveTimers[id]) {
                return;
            }

            clearTimeout(this.leaveTimers[id]);
            delete this.leaveTimers[id];
        },
        resolveTransitionDurationMs() {
            const cssDuration = getComputedStyle(document.documentElement)
                .getPropertyValue('--default-transition-duration')
                .trim();

            if (!cssDuration) {
                return 200;
            }

            if (cssDuration.endsWith('ms')) {
                const milliseconds = Number.parseFloat(cssDuration);

                return Number.isFinite(milliseconds) ? milliseconds : 200;
            }

            if (cssDuration.endsWith('s')) {
                const seconds = Number.parseFloat(cssDuration);

                return Number.isFinite(seconds) ? seconds * 1000 : 200;
            }

            const fallback = Number.parseFloat(cssDuration);

            return Number.isFinite(fallback) ? fallback * 1000 : 200;
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
                duration: Number.isFinite(Number(payload.duration)) ? Math.max(Number(payload.duration), 0) : 5000,
                dismissible: payload.dismissible ?? true,
                visible: false,
            };
        },
    }"
    class="toast-stack"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <article
            class="toast transition-opacity"
            :class="{
                'toast-success': toast.variant === 'success',
                'toast-warning': toast.variant === 'warning',
                'toast-error': toast.variant === 'error',
            }"
            role="status"
            x-show="toast.visible"
            x-transition:enter="transition-opacity"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
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
