<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Informasi Pelaporan Notaris') }} — Kanwil Kemenkumham Bengkulu</title>
        <meta
            name="description"
            content="Portal pelaporan notaris bulanan dan tahunan Kantor Wilayah Kementerian Hukum dan HAM Bengkulu."
        >
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link
            href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
            rel="stylesheet"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>

            /* =========================================================
               ANIMASI LAPORIS
            ========================================================= */

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

            /* =========================================================
               HERO LAPORIS
            ========================================================= */

            .laporis-hero {
                position: relative;
                overflow: hidden;
                /*
                 * Tinggi dibuat lebih padat supaya keseluruhan hero
                 * tidak terlihat renggang.
                 */
                min-height: 620px;
                background:
                    linear-gradient(
                        90deg,
                        rgba(2, 12, 43, 0.94) 0%,
                        rgba(4, 18, 57, 0.88) 35%,
                        rgba(4, 18, 57, 0.78) 65%,
                        rgba(2, 10, 34, 0.90) 100%
                    ),
                    url('{{ asset('images/bg-kanwil-bengkulu.png') }}')
                    center center / cover no-repeat;

            }
            .laporis-hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background:

                    radial-gradient(
                        circle at 50% 45%,
                        rgba(30, 102, 255, 0.18),
                        transparent 40%
                    );

                pointer-events: none;
            }

            .laporis-hero::after {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 220px;
                background:
                    linear-gradient(
                        to top,
                        rgba(1, 12, 40, 0.95),
                        transparent
                    );
                pointer-events: none;
            }
            .laporis-hero-content {
                position: relative;
                z-index: 10;
            }

            /*
             * ========================================================
             * PEMADATAN HERO
             * ========================================================
             *
             * Ini yang membuat bagian setelah header tidak renggang.
             * Tidak mengubah struktur HTML.
             */

            .laporis-hero-content > .mx-auto.max-w-container {
                padding-top: 10px !important;
                padding-bottom: 18px !important;
            }

            .laporis-hero-content .max-w-4xl {
                margin-top: 0 !important;
            }

            /* =========================================================
               LOGO LAPORIS
            ========================================================= */

            .laporis-logo-main {
                width: min(430px, 80vw);
                max-height: 145px;
                object-fit: contain;
                filter:
                    drop-shadow(0 10px 25px rgba(0, 0, 0, 0.45))
                    drop-shadow(0 0 18px rgba(250, 204, 21, 0.10));

            }

            /*
             * Sistem Informasi
             */

            .laporis-system-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 7px 15px;
                border: 1px solid rgba(255, 255, 255, 0.20);
                border-radius: 9999px;
                background: rgba(7, 25, 65, 0.82);
                box-shadow:
                    0 8px 24px rgba(0, 0, 0, 0.18),
                    inset 0 1px 0 rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);

            }

            .laporis-system-dot {
                width: 8px;
                height: 8px;
                border-radius: 9999px;
                background: #facc15;
                box-shadow: 0 0 12px rgba(250, 204, 21, 0.8);

            }

            /*
             * ========================================================
             * SPACING HERO
             * ========================================================
             *
             * Dibuat rapat dan konsisten.
             */

            .laporis-hero-content .flex.justify-center {
                margin-bottom: 0 !important;
            }
            .laporis-hero-content .laporis-logo-main {
                margin-top: 0 !important;
            }
            .laporis-hero-content h1 {
                margin-top: 2px !important;
            }
            .laporis-hero-content h1 + p {
                margin-top: 12px !important;
            }
            .laporis-hero-content .laporis-feature {
                margin-top: 0 !important;
            }

            /* =========================================================
               FITUR
            ========================================================= */

            .laporis-feature {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: 7px 12px;
                border: 1px solid rgba(255, 255, 255, 0.16);
                border-radius: 9999px;
                background: rgba(5, 22, 60, 0.72);
                color: rgba(255, 255, 255, 0.95);
                font-size: 12px;
                font-weight: 600;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);

            }

            .laporis-feature-icon {

                display: inline-flex;

                align-items: center;

                justify-content: center;

                width: 20px;

                height: 20px;

                border-radius: 9999px;

                color: #facc15;

            }

            /*
             * ========================================================
             * AREA FITUR
             * ========================================================
             */

            .laporis-hero-content .mt-7 {

                margin-top: 8px !important;

            }

            /*
             * ========================================================
             * REGION CARD
             * ========================================================
             */

            .laporis-region-card {

                position: relative;

                overflow: hidden;

                min-height: 150px;

                border: 1px solid rgba(255, 255, 255, 0.22);

                border-radius: 18px;

                background:

                    linear-gradient(
                        145deg,
                        rgba(19, 53, 111, 0.88),
                        rgba(5, 24, 63, 0.82)
                    );

                box-shadow:

                    0 15px 35px rgba(0, 0, 0, 0.25),

                    inset 0 1px 0 rgba(255, 255, 255, 0.08);

                backdrop-filter: blur(12px);

                -webkit-backdrop-filter: blur(12px);

                transition:

                    transform 200ms ease,

                    border-color 200ms ease,

                    box-shadow 200ms ease;

            }

            .laporis-region-card:hover {

                transform: translateY(-5px);

                border-color: rgba(250, 204, 21, 0.55);

                box-shadow:

                    0 20px 40px rgba(0, 0, 0, 0.32),

                    0 0 25px rgba(250, 204, 21, 0.08);

            }

            .laporis-region-card::before {

                content: "";

                position: absolute;

                left: 0;

                right: 0;

                top: 0;

                height: 2px;

                background:

                    linear-gradient(
                        90deg,
                        transparent,
                        rgba(250, 204, 21, 0.8),
                        transparent
                    );

            }

            .laporis-region-button {

                display: inline-flex;

                align-items: center;

                justify-content: center;

                gap: 7px;

                padding: 8px 15px;

                border-radius: 9999px;

                background: #facc15;

                color: #071633;

                font-size: 12px;

                font-weight: 800;

                transition:

                    transform 150ms ease,

                    background 150ms ease;

            }

            .laporis-region-card:hover .laporis-region-button {

                background: #fde047;

                transform: translateX(2px);

            }

            /*
             * Jarak kartu wilayah dari fitur dibuat lebih dekat.
             */

            .laporis-hero-content .max-w-5xl {

                margin-top: 28px !important;

            }

            /*
             * Jarak statistik dari kartu wilayah.
             */

            .laporis-hero-content .max-w-3xl {

                margin-top: 20px !important;

            }

            .laporis-stat-wrapper {

                border-top: 1px solid rgba(255, 255, 255, 0.10);

                border-bottom: 1px solid rgba(255, 255, 255, 0.10);

            }

            /* =========================================================
               LAPTOP DIHILANGKAN
            ========================================================= */

            .laporis-laptop {

                display: none !important;

            }

            .laporis-hero-glow {

                position: absolute;

                z-index: 2;

                width: 500px;

                height: 500px;

                right: 5%;

                bottom: -300px;

                border-radius: 9999px;

                background: rgba(37, 99, 235, 0.35);

                filter: blur(90px);

                pointer-events: none;

            }

            /* =========================================================
               RESPONSIVE
            ========================================================= */

            @media (max-width: 1024px) {

                .laporis-hero {

                    min-height: 600px;

                }

            }

            @media (max-width: 768px) {

                .laporis-hero {

                    min-height: auto;

                }

                .laporis-laptop {

                    display: none !important;

                }

                .laporis-logo-main {

                    width: min(360px, 85vw);

                    max-height: 130px;

                }

                .laporis-hero-content {

                    padding-top: 0;

                }

                .laporis-hero-content > .mx-auto.max-w-container {

                    padding-top: 10px !important;

                    padding-bottom: 20px !important;

                }

                .laporis-feature {

                    justify-content: center;

                }

                .laporis-hero-content .max-w-5xl {

                    margin-top: 24px !important;

                }

                .laporis-hero-content .max-w-3xl {

                    margin-top: 18px !important;

                }

            }

        </style>

    </head>

    <body class="font-sans text-gray-900 antialiased flex flex-col min-h-screen">

        <x-layouts.partials.header title="Portal Pelaporan Notaris" />

        @auth
            <div class="border-b border-emas-500 bg-emas-50">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-2.5 text-center text-sm">
                    <span class="font-semibold text-kumham-800">
                        Selamat datang kembali, {{ auth()->user()->name }}.
                    </span>

                    <a
                        href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        class="font-bold text-kumham-700 underline underline-offset-4 hover:text-kumham-500"
                    >
                        Masuk ke Dashboard &rarr;

                    </a>

                </div>

            </div>

        @endauth

        <main class="flex-1">

            {{-- =========================================================
                 HERO
            ========================================================== --}}

            <section class="laporis-hero">

                {{-- Glow dekorasi --}}

                <div class="laporis-hero-glow"></div>

                {{-- Laptop sengaja tidak digunakan lagi --}}

                <div class="laporis-hero-content">

                    <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-14 sm:py-16 lg:py-20">

                        {{-- =================================================
                             AREA UTAMA
                        ================================================== --}}

                        <div class="mx-auto max-w-4xl text-center">

                            {{-- Badge Sistem Informasi --}}

                            <div class="flex justify-center">

                                <div class="laporis-system-badge">

                                    <span class="laporis-system-dot"></span>

                                    <svg
                                        class="h-4 w-4 text-emas-300"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />

                                    </svg>

                                    <span class="text-xs font-semibold tracking-wide text-white">

                                        Sistem Informasi

                                    </span>

                                </div>

                            </div>

                            {{-- =================================================
                                 LOGO LAPORIS
                                 DIBUAT LEBIH DEKAT DENGAN SISTEM INFORMASI
                            ================================================== --}}

                            <div class="mt-0 flex justify-center">

                                <img
                                    src="{{ asset('images/logo-laporis.png') }}"
                                    alt="LAPORIS - Laporan Online Notaris"
                                    class="laporis-logo-main"
                                >

                            </div>

                            {{-- =================================================
                                 CAPSULE DIHAPUS
                            ================================================== --}}

                            {{-- Judul --}}

                            <h1 class="mx-auto mt-7 max-w-4xl text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">

                                Pelaporan Notaris, Kini

                                <br>

                                <span class="text-emas-300">

                                    Lebih Cepat, Transparan &amp; Terintegrasi

                                </span>

                            </h1>

                            {{-- Deskripsi --}}

                            <p class="mx-auto mt-5 max-w-3xl text-sm leading-relaxed text-white sm:text-base">

                                Sampaikan laporan bulanan dan tahunan secara digital kepada Majelis Pengawas Daerah

                                melalui portal resmi Kantor Wilayah Bengkulu Kementerian Hukum Republik Indonesia.

                            </p>

                            {{-- =================================================
                                 FITUR UTAMA
                            ================================================== --}}

                            <div class="mt-7 flex flex-wrap justify-center gap-2.5">

                                {{-- Aman --}}

                                <div class="laporis-feature">

                                    <span class="laporis-feature-icon">

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.052-.382-3.016z"
                                            />

                                        </svg>

                                    </span>

                                    Aman &amp; Terpercaya

                                </div>

                                {{-- Efisien --}}

                                <div class="laporis-feature">

                                    <span class="laporis-feature-icon">

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />

                                        </svg>

                                    </span>

                                    Efisien &amp; Praktis

                                </div>

                                {{-- Terintegrasi --}}

                                <div class="laporis-feature">
                                    <span class="laporis-feature-icon">

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4 6h16M4 12h16M4 18h16"
                                            />
                                        </svg>
                                    </span>

                                    Terintegrasi Data
                                </div>
                            </div>
                        </div>
                        {{-- =====================================================
                             3 WILAYAH / PORTAL
                        ====================================================== --}}
                        <div class="mx-auto mt-10 max-w-5xl">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                @foreach ([

                                    [

                                        'name' => 'SEMAKUTENG',

                                        'slug' => 'semakuteng',

                                        'desc' => 'SELUMA, MANNA, KAUR, BENGKULU TENGAH'

                                    ],

                                    [

                                        'name' => 'RELEPARMU',

                                        'slug' => 'releparmu',

                                        'desc' => 'REJANG LEBONG, LEBONG, KEPAHIANG, ARGAMAKMUR, MUKOMUKO'

                                    ],

                                    [

                                        'name' => 'KOTA BENGKULU',

                                        'slug' => 'kota-bengkulu',

                                        'desc' => 'Kota Bengkulu & sekitarnya'

                                    ],

                                ] as $wilayah)

                                    <a
                                        href="{{ route('login', ['slug' => $wilayah['slug']]) }}"
                                        class="laporis-region-card group flex flex-col justify-between p-5 text-left"
                                    >

                                        {{-- Area icon/gambar
                                             DIBIARKAN KOSONG
                                        --}}

                                        <div class="mb-4 h-1"></div>
                                        <div>
                                            <p class="text-base font-extrabold uppercase tracking-wide text-emas-300 transition group-hover:text-emas-200">

                                                {{ $wilayah['name'] }}
                                            </p>

                                            <p class="mt-1 text-xs leading-relaxed text-white">

                                                {{ $wilayah['desc'] }}
                                            </p>

                                        </div>
                                        <div class="mt-5 flex items-center justify-between">

                                            <span class="text-xs font-bold text-white">

                                                Masuk Portal

                                            </span>

                                            <span class="laporis-region-button">

                                                <span>

                                                    Masuk Portal

                                                </span>

                                                <svg
                                                    class="h-3.5 w-3.5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2.5"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                                                    />
                                                </svg>
                                            </span>
                                        </div>
                                    </a>

                                @endforeach
                            </div>
                        </div>

                        {{-- =====================================================
                             STATISTIK LAMA — TETAP DIPERTAHANKAN
                        ====================================================== --}}

                        <div class="mx-auto mt-10 max-w-3xl">
                            <div class="laporis-stat-wrapper grid grid-cols-1 overflow-hidden sm:grid-cols-3">
                                @foreach ([

                                    [

                                        'value' => '3',

                                        'label' => 'Wilayah Pengawasan'

                                    ],

                                    [

                                        'value' => 'SIMAKUTENG · RELEPARMU',

                                        'label' => 'KOTA BENGKULU'

                                    ],

                                    [

                                        'value' => '100%',

                                        'label' => 'Laporan Digital'

                                    ],

                                ] as $stat)
                                    <div class="px-6 py-5 text-center">
                                        <p class="text-xl font-extrabold text-emas-300">

                                            {{ $stat['value'] }}
                                        </p>
                                        <p class="mt-1 text-xs text-white">

                                            {{ $stat['label'] }}
                                        </p>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- =========================================================
                 LAYANAN
                 TIDAK DIHAPUS / TIDAK DIUBAH
            ========================================================== --}}

            <section class="bg-gray-50 py-20">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-extrabold tracking-tight text-kumham-950">

                            Layanan untuk Notaris &amp; Pengawas
                        </h2>
                        <p class="mt-3 text-gray-600 leading-relaxed">
                            Seluruh proses pelaporan dan pengawasan dilakukan dalam satu sistem terpadu.
                        </p>
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

                        @foreach ([

                            [

                                'title' => 'Laporan Bulanan',

                                'desc' => 'Kirim laporan akta, legalisasi, waarmerking, wasiat, dan protes setiap bulan.',

                                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'

                            ],

                            [

                                'title' => 'Unggah PDF',

                                'desc' => 'Lampirkan file laporan PDF langsung dari akun Anda, aman dan tersimpan terpusat.',

                                'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'

                            ],

                            [

                                'title' => 'Rekapitulasi',

                                'desc' => 'Data tahunan dan bulanan tersaji otomatis untuk kebutuhan MPD dan Kantor Wilayah.',

                                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'

                            ],

                            [

                                'title' => 'Kepatuhan',

                                'desc' => 'Pantau notaris yang belum melapor per wilayah, bulan, dan tahun.',

                                'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'

                            ],

                        ] as $service)

                            <div class="card-panel group p-6 transition duration-200 hover:-translate-y-1 hover:shadow-lg">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-kumham-700 text-white transition duration-200 group-hover:bg-kumham-600">
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="{{ $service['icon'] }}"
                                        />
                                    </svg>
                                </span>
                                <h3 class="mt-5 font-bold text-kumham-950">
                                    {{ $service['title'] }}
                                </h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-600">

                                    {{ $service['desc'] }}
                                </p>
                            </div>

                        @endforeach
                    </div>
                </div>
            </section>

            {{-- =========================================================
                 WILAYAH
                 TIDAK DIHAPUS / TIDAK DIUBAH
            ========================================================== --}}

            <section class="bg-white py-20">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-extrabold tracking-tight text-kumham-950">

                            Wilayah Pengawasan Daerah
                        </h2>
                        <p class="mt-3 text-gray-600 leading-relaxed">
                            Notaris ditempatkan pada salah satu Majelis Pengawas Daerah sesuai domisili praktiknya.
                        </p>
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                        @foreach ([

                            [

                                'name' => 'SIMAKUTENG',

                                'desc' => 'Seluma, Bengkulu Selatan, Manna, dan Kaur'

                            ],

                            [

                                'name' => 'RELEPARMU',

                                'desc' => 'Rejang Lebong, Lebong, dan Kepahiang'

                            ],

                            [

                                'name' => 'KOTA BENGKULU',

                                'desc' => 'Kota Bengkulu dan sekitarnya'

                            ],

                        ] as $i => $wilayah)
                            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-6">
                                <span class="absolute inset-y-0 left-0 w-1 bg-emas-500"></span>
                                <span class="text-sm font-bold uppercase tracking-wider text-kumham-500">

                                    Wilayah 0{{ $i + 1 }}
                                </span>
                                <h3 class="mt-2 text-xl font-extrabold text-kumham-950">

                                    {{ $wilayah['name'] }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-600">

                                    {{ $wilayah['desc'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- =========================================================
                 CARA KERJA
                 TIDAK DIHAPUS / TIDAK DIUBAH
            ========================================================== --}}

            <section class="institutional-header text-white">
                <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-20">
                    <div class="max-w-2xl">
                        <p class="section-eyebrow mb-5 !bg-white/10 !text-emas-300">
                            Tata Cara
                        </p>
                        <h2 class="text-3xl font-extrabold tracking-tight">
                            Tiga Langkah Melapor
                        </h2>
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-3">
                        @foreach ([

                            [

                                'step' => '01',

                                'title' => 'Didaftarkan Admin Wilayah',

                                'desc' => 'Akun notaris dibuat oleh admin wilayah sesuai tempat praktik Anda.'

                            ],

                            [

                                'step' => '02',

                                'title' => 'Isi Laporan & Unggah PDF',

                                'desc' => 'Lengkapi jumlah akta dan dokumen, lalu lampirkan file laporan PDF.'

                            ],

                            [
                                'step' => '03',

                                'title' => 'Terpantau oleh Pengawas',

                                'desc' => 'Laporan Anda langsung tercatat dan dapat diverifikasi oleh admin.'

                            ],

                        ] as $cara)

                            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                                <span class="text-4xl font-extrabold text-emas-400/80">

                                    {{ $cara['step'] }}

                                </span>
                                <h3 class="mt-4 text-lg font-bold">

                                    {{ $cara['title'] }}

                                </h3>
                                <p class="mt-2 text-sm leading-relaxed text-white/70">

                                    {{ $cara['desc'] }}

                                </p>
                            </div>

                        @endforeach

                    </div>
                </div>
            </section>
        </main>
        <x-layouts.partials.footer />
    </body>
</html>
