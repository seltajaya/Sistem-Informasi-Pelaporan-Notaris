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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen lg:grid lg:grid-cols-[1.1fr_1fr]">
            <!-- Institutional panel -->
            <div
                class="institutional-header relative overflow-hidden flex flex-col justify-between p-8 sm:p-12 text-white bg-cover bg-center"
                style="background-image: linear-gradient(rgba(4, 22, 67, 0.86), rgba(15, 49, 125, 0.94)), url('{{ asset('images/bg-kanwil-bengkulu.png') }}');"
            >
                <div class="absolute inset-x-0 top-0 h-1.5 flex" aria-hidden="true">
                    <span class="flex-1 bg-red-600"></span>
                    <span class="flex-1 bg-white"></span>
                    <span class="flex-1 bg-red-600"></span>
                    <span class="flex-1 bg-white"></span>
                </div>

                <div>
                    <x-layouts.partials.logo dark />
                </div>

                <div class="max-w-md">
                    <p class="section-eyebrow mb-4 !bg-white/10 !text-emas-300">Layanan Digital</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight tracking-tight">
                        Pelaporan Notaris Bulanan &amp; Tahunan, kini secara digital.
                    </h1>
                    <p class="mt-4 text-white/75 leading-relaxed">
                        Notaris di wilayah SEMAKUTENG, RELEPARMU, dan KOTA BENGKULU dapat menyampaikan
                        laporan berkala lengkap dengan dokumen pendukung tanpa harus datang ke kantor.
                    </p>
                    <ul class="mt-8 space-y-3 text-sm text-white/80">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emas-400 text-kumham-900">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </span>
                            Isi laporan terstruktur dalam hitungan menit
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emas-400 text-kumham-900">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </span>
                            Unggah dokumen PDF langsung dari akun Anda
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emas-400 text-kumham-900">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </span>
                            Pantau status kepatuhan oleh pengawas daerah
                        </li>
                    </ul>
                </div>

                <p class="text-xs text-white/50">&copy; {{ now()->year }} Kanwil Kemenkum Bengkulu</p>
            </div>

            <!-- Form panel -->
            <div class="flex items-center justify-center bg-gray-50 p-6 sm:p-12">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html> 