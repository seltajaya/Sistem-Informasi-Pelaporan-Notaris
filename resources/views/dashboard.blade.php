<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-kumham-700">
                    Dashboard Notaris
                </p>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">
                    Halo, {{ auth()->user()->name }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Wilayah penempatan:
                    <span class="font-semibold text-kumham-700">
                        {{ auth()->user()->region?->name ?? '-' }}
                    </span>
                </p>
            </div>

            <a href="{{ route('reports.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-kumham-700 px-5 py-2.5 text-sm font-bold uppercase tracking-wider text-white shadow-card transition duration-200 hover:bg-kumham-600 active:scale-[0.98]">

                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4v16m8-8H4" />
                </svg>

                Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('status'))
                <div
                    class="mb-6 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">

                    <svg class="h-5 w-5 shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    {{ session('status') }}
                </div>
            @endif


            {{-- Panduan Pelaporan --}}
            <div class="card-panel overflow-hidden">

                <div class="border-b border-gray-200 px-5 py-4">
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-kumham-50 text-kumham-700">

                            <svg class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>

                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-kumham-900">
                                Panduan Pelaporan
                            </h3>

                            <p class="text-xs text-gray-500">
                                Tiga langkah mudah untuk menyampaikan laporan.
                            </p>
                        </div>

                    </div>
                </div>


                {{-- Tiga Langkah --}}
                <div class="px-5 py-4">

                    <div class="grid gap-3 md:grid-cols-3">

                        {{-- Langkah 01 --}}
                        <div
                            class="rounded-lg border border-gray-200 bg-white px-4 py-3">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-kumham-800 text-[10px] font-bold text-white">
                                    01
                                </span>

                                <div>
                                    <h4 class="text-sm font-bold text-kumham-900">
                                        Isi Laporan
                                    </h4>

                                    <p class="mt-0.5 text-xs leading-relaxed text-gray-500">
                                        Lengkapi data laporan sesuai kondisi sebenarnya.
                                    </p>
                                </div>

                            </div>
                        </div>


                        {{-- Langkah 02 --}}
                        <div
                            class="rounded-lg border border-gray-200 bg-white px-4 py-3">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-kumham-800 text-[10px] font-bold text-white">
                                    02
                                </span>

                                <div>
                                    <h4 class="text-sm font-bold text-kumham-900">
                                        Unggah Dokumen
                                    </h4>

                                    <p class="mt-0.5 text-xs leading-relaxed text-gray-500">
                                        Unggah dokumen pendukung dalam format PDF.
                                    </p>
                                </div>

                            </div>
                        </div>


                        {{-- Langkah 03 --}}
                        <div
                            class="rounded-lg border border-gray-200 bg-white px-4 py-3">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-kumham-800 text-[10px] font-bold text-white">
                                    03
                                </span>

                                <div>
                                    <h4 class="text-sm font-bold text-kumham-900">
                                        Kirim Laporan
                                    </h4>

                                    <p class="mt-0.5 text-xs leading-relaxed text-gray-500">
                                        Periksa data lalu kirim laporan.
                                    </p>
                                </div>

                            </div>
                        </div>

                    </div>


                    {{-- Perhatian --}}
                    <div
                        class="mt-3 flex items-center gap-3 rounded-lg border border-emas-200 bg-emas-50 px-3 py-2">

                        <svg class="h-4 w-4 shrink-0 text-emas-700"
                            fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.5m0 3h.01M10.29 3.86l-8.02 14A2 2 0 003.99 21h16.02a2 2 0 001.72-3.14l-8.02-14a2 2 0 00-3.42 0z" />
                        </svg>

                        <p class="text-xs text-gray-600">
                            <span class="font-bold text-kumham-900">
                                Perhatian:
                            </span>
                            Pastikan laporan telah diperiksa dengan benar sebelum dikirim.
                        </p>

                    </div>

                </div>
            </div>


            {{-- Kepatuhan Pelaporan --}}
            @if ($totalNotaris > 0)
                <div class="card-panel mt-5 overflow-hidden">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h3 class="text-sm font-bold text-kumham-900">
                                Kepatuhan Pelaporan Wilayah {{ $regionName }}
                            </h3>
                            <span class="inline-flex rounded-full bg-kumham-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-kumham-700">
                                {{ \Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }} {{ $year }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Berapa banyak notaris yang sudah melapor bulan ini di wilayah Anda.
                        </p>
                    </div>

                    <div class="space-y-4 px-5 py-5">
                        {{-- Bar Sudah Melapor --}}
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-semibold text-green-700">Sudah Melapor</span>
                                <span class="font-bold text-kumham-900">
                                    {{ $sudahMelapor }} dari {{ $totalNotaris }}
                                    ({{ $totalNotaris ? round(($sudahMelapor / $totalNotaris) * 100) : 0 }}%)
                                </span>
                            </div>
                            <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-green-500 transition-all"
                                    style="width: {{ $totalNotaris ? round(($sudahMelapor / $totalNotaris) * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        {{-- Bar Belum Melapor --}}
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-semibold text-red-700">Belum Melapor</span>
                                <span class="font-bold text-kumham-900">
                                    {{ $belumMelapor }} dari {{ $totalNotaris }}
                                    ({{ $totalNotaris ? round(($belumMelapor / $totalNotaris) * 100) : 0 }}%)
                                </span>
                            </div>
                            <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-red-500 transition-all"
                                    style="width: {{ $totalNotaris ? round(($belumMelapor / $totalNotaris) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Belum Melapor --}}
                    @if ($daftarBelum->isNotEmpty())
                        <div class="border-t border-red-100 bg-red-50/50 px-5 py-4">
                            <p class="mb-3 text-xs font-bold uppercase tracking-wider text-red-700">
                                Daftar notaris yang belum melapor:
                            </p>
                            <ul class="space-y-1.5">
                                @foreach ($daftarBelum as $notaris)
                                    <li class="flex items-center gap-2 text-sm text-gray-700">
                                        <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-[10px] font-bold text-red-700">!</span>
                                        {{ $notaris->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="border-t border-green-100 bg-green-50/50 px-5 py-4">
                            <p class="text-sm font-semibold text-green-700">
                                Semua notaris di wilayah ini sudah melapor.
                            </p>
                        </div>
                    @endif
                </div>
            @endif


            {{-- Butuh Bantuan --}}
            <div class="mt-5">

                <div class="card-panel p-5">

                    <div class="flex items-center justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-kumham-50 text-kumham-700">

                                <svg class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M18.36 5.64A9 9 0 115.64 18.36 9 9 0 0118.36 5.64z" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.5 9a2.5 2.5 0 015 0c0 1.67-2.5 2-2.5 3.5m0 3h.01" />
                                </svg>

                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-kumham-900">
                                    Butuh Bantuan?
                                </h3>

                                <p class="mt-0.5 text-xs text-gray-500">
                                    Hubungi admin jika mengalami kendala dalam pengisian atau pengiriman laporan.
                                </p>
                            </div>

                        </div>

                        <a href="mailto:kanwil.bengkulu@kemenkumham.go.id"
                            class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-kumham-800 transition hover:border-kumham-300 hover:bg-kumham-50">

                            <svg class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>

                            Hubungi Admin
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
