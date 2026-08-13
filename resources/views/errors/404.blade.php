<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Halaman Tidak Ditemukan — {{ config('app.name') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased flex flex-col min-h-screen">
        <x-layouts.partials.header />

        <main class="flex flex-1 items-center justify-center bg-gray-50 px-4 py-20">
            <div class="max-w-lg text-center">
                <p class="text-7xl font-extrabold tracking-tight text-kumham-100">404</p>
                <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-kumham-950">Halaman tidak ditemukan</h1>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    Halaman yang Anda cari tidak tersedia atau telah dipindahkan.
                    Gunakan navigasi untuk kembali ke halaman yang benar.
                </p>
                <div class="mt-8 flex items-center justify-center gap-4">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-kumham-700 px-5 py-2.5 text-sm font-bold uppercase tracking-wider text-white transition duration-200 hover:bg-kumham-600 active:scale-[0.98]">
                        &larr; Beranda
                    </a>
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="text-sm font-semibold text-kumham-700 hover:text-kumham-500">
                            Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </main>

        <x-layouts.partials.footer />
    </body>
</html>
