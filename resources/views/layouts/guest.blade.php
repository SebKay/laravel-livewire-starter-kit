<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-brand-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css'])

    @livewireStyles
</head>

<body class="h-full font-text text-neutral-700">
    <div class="md:min-h-full flex flex-col justify-center">
        <main class="xl:py-16 py-8 px-4 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-7xl">
                <x-lucide-sparkles class="size-10 mx-auto text-brand-800 block mb-4 xl:mb-8" />

                {{ $slot }}
            </div>
        </main>
    </div>

    <x-toast-stack />

    @livewireScripts
</body>

</html>
