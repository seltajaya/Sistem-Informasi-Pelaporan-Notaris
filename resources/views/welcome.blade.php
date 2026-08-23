<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Informasi Pelaporan Notaris') }} — Kanwil Kemenkumham Bengkulu</title>
        <meta name="description" content="Portal pelaporan notaris bulanan dan tahunan Kantor Wilayah Kementerian Hukum dan HAM Bengkulu.">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Animasi kilau LAPORIS */
            @keyframes laporisShine {
                0% {
                    background-position: 200% center;
                }
                100% {
                    background-position: -200% center;
                }
            }

            .laporis-title {
                display: inline-block;
                background: linear-gradient(
                    110deg,
                    #facc15 0%,
                    #facc15 35%,
                    #fff7b2 45%,
                    #ffffff 50%,
                    #fff7b2 55%,
                    #facc15 65%,
                    #facc15 100%
                );
                background-size: 250% auto;
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: laporisShine 3.5s linear infinite;
            }

            .laporis-capsule {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 7px 18px;
                border: 1px solid rgba(250, 204, 21, 0.28);
                border-radius: 9999px;
                background: rgba(255, 255, 255, 0.08);
                box-shadow:
                    0 8px 25px rgba(0, 0, 0, 0.10),
                    inset 0 1px 0 rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }
        </style>
    </head>

    <body class="font-sans text-gray-900 antialiased flex flex-col min-h-screen">
        <x-layouts.partials.header title="Portal Pelaporan Notaris" />

        @auth
            <div class="border-b border-emas-500 bg-emas-50">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-2.5 text-center text-sm">
                    <span class="font-semibold text-kumham-800">Selamat datang kembali, {{ auth()->user()->name }}.</span>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="font-bold text-kumham-700 underline underline-offset-4 hover:text-kumham-500">Masuk ke Dashboard &rarr;</a>
                </div>
            </div>
        @endauth

        <main class="flex-1">
            {{-- Hero --}}
            <section class="institutional-header relative overflow-hidden text-white">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-20 text-center">

                    {{-- Identitas LAPORIS --}}
                    <div class="mx-auto mb-10 flex flex-col items-center">
                        <h2 class="laporis-title text-4xl font-extrabold tracking-tight sm:text-5xl">
                            LAPORIS
                        </h2>

                        <div class="laporis-capsule mt-4">
                            <span class="text-sm font-semibold tracking-wide text-white/90">
                                Laporan Online Notaris
                            </span>
                        </div>
                    </div>

                    <h1 class="mx-auto max-w-3xl text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl text-balance">
                        Pelaporan Notaris, Kini Cepat &amp; Transparan
                    </h1>

                    <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-white/75 sm:text-lg">
                        Sampaikan laporan bulanan dan tahunan secara digital kepada Majelis Pengawas Daerah
                        melalui portal resmi Kantor Wilayah Kementerian Hukum dan HAM Bengkulu.
                    </p>
                    
                    <div class="mt-8 flex justify-center">
                        <div class="grid w-full max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
                            @foreach ([
                                ['name' => 'SEMAKUTENG', 'slug' => 'semakuteng', 'desc' => 'Seluma, Bengkulu Selatan, Manna, Kaur'],
                                ['name' => 'RELEPARMU', 'slug' => 'releparmu', 'desc' => 'Rejang Lebong, Lebong, Kepahiang'],
                                ['name' => 'KOTA BENGKULU', 'slug' => 'kota-bengkulu', 'desc' => 'Kota Bengkulu & sekitarnya'],
                            ] as $wilayah)
                                <a href="{{ route('login', ['slug' => $wilayah['slug']]) }}"
                                    class="group flex flex-col justify-between rounded-xl border border-emas-500/30 bg-kumham-900/40 p-5 text-left backdrop-blur-md transition duration-200 hover:bg-kumham-800/80 hover:border-emas-400 hover:shadow-xl active:scale-[0.98]">
                                    <div>
                                        <p class="text-lg font-extrabold uppercase tracking-wide text-emas-300 group-hover:text-emas-200">{{ $wilayah['name'] }}</p>
                                        <p class="mt-1 text-sm text-white/70">{{ $wilayah['desc'] }}</p>
                                    </div>
                                    <div class="mt-5 pt-3 border-t border-white/10 flex items-center justify-between text-sm font-bold text-white">
                                        <span class="group-hover:text-emas-300 transition">Masuk Portal</span>
                                        <span class="bg-emas-500 text-kumham-950 px-3 py-1 rounded-lg font-extrabold group-hover:bg-emas-400 transition">&rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-14 flex justify-center">
                        <div class="grid w-full max-w-3xl grid-cols-1 gap-px overflow-hidden rounded-xl bg-white/10 sm:grid-cols-3">
                            @foreach ([
                                ['value' => '3', 'label' => 'Wilayah Pengawasan'],
                                ['value' => 'SEMAKUTENG · RELEPARMU', 'label' => 'KOTA BENGKULU'],
                                ['value' => '100%', 'label' => 'Laporan Digital'],
                            ] as $stat)
                                <div class="bg-kumham-800/60 px-6 py-6 text-center">
                                    <p class="text-2xl font-extrabold text-emas-300">{{ $stat['value'] }}</p>
                                    <p class="mt-1 text-sm text-white/70">{{ $stat['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Layanan --}}
            <section class="bg-gray-50 py-20">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-extrabold tracking-tight text-kumham-950">Layanan untuk Notaris &amp; Pengawas</h2>
                        <p class="mt-3 text-gray-600 leading-relaxed">Seluruh proses pelaporan dan pengawasan dilakukan dalam satu sistem terpadu.</p>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ([
                            ['title' => 'Laporan Bulanan', 'desc' => 'Kirim laporan akta, legalisasi, waarmerking, wasiat, dan protes setiap bulan.', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['title' => 'Unggah PDF', 'desc' => 'Lampirkan file laporan PDF langsung dari akun Anda, aman dan tersimpan terpusat.', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
                            ['title' => 'Rekapitulasi', 'desc' => 'Data tahunan dan bulanan tersaji otomatis untuk kebutuhan MPD dan Kantor Wilayah.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['title' => 'Kepatuhan', 'desc' => 'Pantau notaris yang belum melapor per wilayah, bulan, dan tahun.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ] as $service)
                            <div class="card-panel group p-6 transition duration-200 hover:-translate-y-1 hover:shadow-lg">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-kumham-700 text-white transition duration-200 group-hover:bg-kumham-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $service['icon'] }}" /></svg>
                                </span>
                                <h3 class="mt-5 font-bold text-kumham-950">{{ $service['title'] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $service['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Wilayah --}}
            <section class="bg-white py-20">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-extrabold tracking-tight text-kumham-950">Wilayah Pengawasan Daerah</h2>
                        <p class="mt-3 text-gray-600 leading-relaxed">Notaris ditempatkan pada salah satu Majelis Pengawas Daerah sesuai domisili praktiknya.</p>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                        @foreach ([
                            ['name' => 'SEMAKUTENG', 'desc' => 'Seluma, Bengkulu Selatan, Manna, dan Kaur'],
                            ['name' => 'RELEPARMU', 'desc' => 'Rejang Lebong, Lebong, dan Kepahiang'],
                            ['name' => 'KOTA BENGKULU', 'desc' => 'Kota Bengkulu dan sekitarnya'],
                        ] as $i => $wilayah)
                            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-6">
                                <span class="absolute inset-y-0 left-0 w-1 bg-emas-500"></span>
                                <span class="text-sm font-bold uppercase tracking-wider text-kumham-500">Wilayah 0{{ $i + 1 }}</span>
                                <h3 class="mt-2 text-xl font-extrabold text-kumham-950">{{ $wilayah['name'] }}</h3>
                                <p class="mt-2 text-sm text-gray-600">{{ $wilayah['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Cara kerja --}}
            <section class="institutional-header text-white">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-20">
                    <div class="max-w-2xl">
                        <p class="section-eyebrow mb-5 !bg-white/10 !text-emas-300">Tata Cara</p>
                        <h2 class="text-3xl font-extrabold tracking-tight">Tiga Langkah Melapor</h2>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-3">
                        @foreach ([
                            ['step' => '01', 'title' => 'Didaftarkan Admin Wilayah', 'desc' => 'Akun notaris dibuat oleh admin wilayah sesuai tempat praktik Anda.'],
                            ['step' => '02', 'title' => 'Isi Laporan & Unggah PDF', 'desc' => 'Lengkapi jumlah akta dan dokumen, lalu lampirkan file laporan PDF.'],
                            ['step' => '03', 'title' => 'Terpantau oleh Pengawas', 'desc' => 'Laporan Anda langsung tercatat dan dapat diverifikasi oleh admin.'],
                        ] as $cara)
                            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                                <span class="text-4xl font-extrabold text-emas-400/80">{{ $cara['step'] }}</span>
                                <h3 class="mt-4 text-lg font-bold">{{ $cara['title'] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $cara['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <x-layouts.partials.footer />
    </body>
</html>