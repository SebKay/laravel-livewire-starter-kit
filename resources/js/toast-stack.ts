type ToastVariant = "success" | "warning" | "error";

type ToastPayload = {
    id?: string | null;
    message?: string | null;
    variant?: string | null;
    heading?: string | null;
    duration?: number | string | null;
    dismissible?: boolean | null;
};

type ToastStackConfig = {
    initialToasts: ToastPayload[];
    closeLabel: string;
};

type ToastItem = {
    id: string;
    message: string;
    variant: ToastVariant;
    heading: string | null;
    duration: number;
    dismissible: boolean;
    visible: boolean;
};

type ToastStackComponent = {
    toasts: ToastItem[];
    closeLabel: string;
    init(): void;
    receive(detail: unknown): void;
    push(payload: unknown): void;
    remove(id: string): void;
    destroy(): void;
};

type AlpineFactory = {
    data(
        name: string,
        callback: (config: ToastStackConfig) => ToastStackComponent,
    ): void;
};

const fallbackTransitionDurationMs = 200;
const defaultToastDurationMs = 5000;
const visibleToastLimit = 3;
const validVariants: ToastVariant[] = ["success", "warning", "error"];

const isToastVariant = (variant: unknown): variant is ToastVariant => {
    return validVariants.includes(variant as ToastVariant);
};

const resolveTransitionDurationMs = (): number => {
    const cssDuration = getComputedStyle(document.documentElement)
        .getPropertyValue("--default-transition-duration")
        .trim();

    if (!cssDuration) {
        return fallbackTransitionDurationMs;
    }

    if (cssDuration.endsWith("ms")) {
        const milliseconds = Number.parseFloat(cssDuration);

        return Number.isFinite(milliseconds)
            ? milliseconds
            : fallbackTransitionDurationMs;
    }

    if (cssDuration.endsWith("s")) {
        const seconds = Number.parseFloat(cssDuration);

        return Number.isFinite(seconds)
            ? seconds * 1000
            : fallbackTransitionDurationMs;
    }

    const fallback = Number.parseFloat(cssDuration);

    return Number.isFinite(fallback)
        ? fallback * 1000
        : fallbackTransitionDurationMs;
};

const createToastId = (): string => {
    return `${Date.now()}-${Math.random()}`;
};

const normalizeToast = (payload: unknown): ToastItem | null => {
    if (!payload || typeof payload !== "object") {
        return null;
    }

    const candidate = payload as ToastPayload;

    if (
        typeof candidate.message !== "string" ||
        candidate.message.length === 0
    ) {
        return null;
    }

    const duration = Number(candidate.duration);

    return {
        id:
            typeof candidate.id === "string" && candidate.id.length > 0
                ? candidate.id
                : createToastId(),
        message: candidate.message,
        heading:
            typeof candidate.heading === "string" &&
            candidate.heading.length > 0
                ? candidate.heading
                : null,
        variant: isToastVariant(candidate.variant)
            ? candidate.variant
            : "success",
        duration: Number.isFinite(duration)
            ? Math.max(duration, 0)
            : defaultToastDurationMs,
        dismissible:
            typeof candidate.dismissible === "boolean"
                ? candidate.dismissible
                : true,
        visible: false,
    };
};

document.addEventListener("alpine:init", () => {
    const alpine = (window as Window & { Alpine?: AlpineFactory }).Alpine;

    alpine?.data(
        "toastStack",
        (config: ToastStackConfig): ToastStackComponent => {
            const timers = new Map<string, number>();
            const leaveTimers = new Map<string, number>();
            let transitionDurationMs = fallbackTransitionDurationMs;

            const setToastVisible = (id: string): void => {
                queueMicrotask(() => {
                    const queuedToast = component.toasts.find(
                        (candidate) => candidate.id === id,
                    );

                    if (queuedToast) {
                        queuedToast.visible = true;
                    }
                });
            };

            const clearTimer = (id: string): void => {
                const timer = timers.get(id);

                if (timer === undefined) {
                    return;
                }

                window.clearTimeout(timer);
                timers.delete(id);
            };

            const clearLeaveTimer = (id: string): void => {
                const timer = leaveTimers.get(id);

                if (timer === undefined) {
                    return;
                }

                window.clearTimeout(timer);
                leaveTimers.delete(id);
            };

            const component: ToastStackComponent = {
                toasts: [],
                closeLabel: config.closeLabel,
                init() {
                    transitionDurationMs = resolveTransitionDurationMs();

                    for (const toast of config.initialToasts) {
                        component.push(toast);
                    }
                },
                receive(detail) {
                    const payload = Array.isArray(detail) ? detail[0] : detail;

                    component.push(payload);
                },
                push(payload) {
                    const toast = normalizeToast(payload);

                    if (!toast) {
                        return;
                    }

                    component.toasts.push(toast);
                    setToastVisible(toast.id);

                    if (component.toasts.length > visibleToastLimit) {
                        const oldestToast = component.toasts.find(
                            (candidate) => candidate.id !== toast.id,
                        );

                        if (oldestToast?.id) {
                            component.remove(oldestToast.id);
                        }
                    }

                    if (toast.duration > 0) {
                        timers.set(
                            toast.id,
                            window.setTimeout(
                                () => this.remove(toast.id),
                                toast.duration,
                            ),
                        );
                    }
                },
                remove(id) {
                    clearTimer(id);

                    const toast = component.toasts.find(
                        (candidate) => candidate.id === id,
                    );

                    if (!toast || !toast.visible) {
                        return;
                    }

                    toast.visible = false;
                    clearLeaveTimer(id);

                    leaveTimers.set(
                        id,
                        window.setTimeout(() => {
                            component.toasts = component.toasts.filter(
                                (candidate) => candidate.id !== id,
                            );
                            clearLeaveTimer(id);
                        }, transitionDurationMs),
                    );
                },
                destroy() {
                    for (const id of Array.from(timers.keys())) {
                        clearTimer(id);
                    }

                    for (const id of Array.from(leaveTimers.keys())) {
                        clearLeaveTimer(id);
                    }
                },
            };

            return component;
        },
    );
});
