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
    <div class="flex flex-col justify-center md:min-h-full">
        <main class="px-4 py-8 sm:px-6 xl:px-8 xl:py-16">
            <div class="mx-auto max-w-7xl">
                <x-lucide-sparkles
                    class="mx-auto mb-4 block size-10 text-brand-800 xl:mb-8"
                />

                {{ $slot }}
            </div>
        </main>
    </div>

    <x-toast-stack />

    @livewireScripts
</body>
</html>
