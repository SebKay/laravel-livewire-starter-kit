<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full bg-brand-50"
>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? config('app.name') }}</title>

    @vite (['resources/css/app.css', 'resources/js/app.ts'])

    @livewireStyles
</head>

<body class="h-full font-text text-neutral-700">
    <div class="min-h-full bg-brand-50 lg:pl-72">
        <x-sidebar />

        <main class="px-4 py-4 sm:px-6 lg:px-8 lg:py-8 xl:py-16">
            <div class="mx-auto max-w-7xl">{{ $slot }}</div>
        </main>
    </div>

    <x-toast-stack />

    @livewireScripts
</body>
</html>
