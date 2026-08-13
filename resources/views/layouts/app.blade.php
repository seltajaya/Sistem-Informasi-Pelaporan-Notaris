<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Sistem Informasi Pelaporan Notaris'))</title>
        <meta name="description" content="Sistem Informasi Pelaporan Notaris Kantor Wilayah Kementerian Hukum dan HAM Bengkulu.">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased flex flex-col min-h-screen">
        <x-layouts.partials.header />

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="border-b border-gray-200 bg-white">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-layouts.partials.footer />
    </body>
</html>
