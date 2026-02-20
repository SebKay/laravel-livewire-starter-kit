<?php

namespace App\Livewire\Concerns;

trait InteractsWithToasts
{
    protected function toastSuccess(string $message, ?string $heading = null, ?int $duration = null): void
    {
        $this->dispatchToast($this->buildToastPayload(
            message: $message,
            variant: 'success',
            heading: $heading,
            duration: $duration,
        ));
    }

    protected function toastWarning(string $message, ?string $heading = null, ?int $duration = null): void
    {
        $this->dispatchToast($this->buildToastPayload(
            message: $message,
            variant: 'warning',
            heading: $heading,
            duration: $duration,
        ));
    }

    protected function toastError(string $message, ?string $heading = null, ?int $duration = null): void
    {
        $this->dispatchToast($this->buildToastPayload(
            message: $message,
            variant: 'error',
            heading: $heading,
            duration: $duration,
        ));
    }

    protected function flashToastSuccess(string $message, ?string $heading = null, ?int $duration = null): void
    {
        $this->flashToast($this->buildToastPayload(
            message: $message,
            variant: 'success',
            heading: $heading,
            duration: $duration,
        ));
    }

    protected function flashToastWarning(string $message, ?string $heading = null, ?int $duration = null): void
    {
        $this->flashToast($this->buildToastPayload(
            message: $message,
            variant: 'warning',
            heading: $heading,
            duration: $duration,
        ));
    }

    protected function flashToastError(string $message, ?string $heading = null, ?int $duration = null): void
    {
        $this->flashToast($this->buildToastPayload(
            message: $message,
            variant: 'error',
            heading: $heading,
            duration: $duration,
        ));
    }

    protected function buildToastPayload(
        string $message,
        string $variant,
        ?string $heading = null,
        ?int $duration = null,
        bool $dismissible = true,
    ): array {
        return [
            'id' => (string) str()->ulid(),
            'message' => $message,
            'variant' => in_array($variant, ['success', 'warning', 'error'], true) ? $variant : 'success',
            'heading' => $heading,
            'duration' => max(0, $duration ?? 5000),
            'dismissible' => $dismissible,
        ];
    }

    protected function dispatchToast(array $toast): void
    {
        $this->dispatch(
            'toast',
            id: $toast['id'],
            message: $toast['message'],
            variant: $toast['variant'],
            heading: $toast['heading'],
            duration: $toast['duration'],
            dismissible: $toast['dismissible'],
        );
    }

    protected function flashToast(array $toast): void
    {
        session()->flash(
            'toasts',
            array_merge(session('toasts', []), [$toast]),
        );
    }
}
