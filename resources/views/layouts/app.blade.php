<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="asepjourney">
    <meta name="description" content="Stock UMKM — Sistem Manajemen Inventaris Profesional untuk UMKM Indonesia. Crafted with care by asepjourney.">
    <meta name="theme-color" content="#6366F1">

    <title>@yield('title', 'Dashboard') · StockUMKM — by asepjourney</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @stack('head')
</head>

<body class="aj-app font-sans antialiased text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col">
        @include('partials.navbar')

        <main class="flex-1 relative">
            <div class="absolute inset-x-0 top-0 h-64 pointer-events-none aj-dotgrid opacity-40"></div>
            <div class="relative">
                @yield('content')
            </div>
        </main>

        @include('partials.footer')
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
