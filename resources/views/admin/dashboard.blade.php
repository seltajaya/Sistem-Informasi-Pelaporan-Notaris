<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Dashboard Admin</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Ringkasan Kepatuhan Pelaporan</h2>
            <p class="mt-1 text-sm text-gray-500">Pemantauan laporan notaris per wilayah.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
            <form method="GET" class="card-panel mb-6 flex flex-wrap items-end gap-4 p-4">
                <div>
                    <x-input-label for="month" :value="__('Bulan')" />
                    <select id="month" name="month" class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @selected($month == $i)>
                                {{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <x-input-label for="year" :value="__('Tahun')" />
                    <x-text-input id="year" class="mt-1" type="number" name="year" min="2000" max="2100" :value="$year" />
                </div>
                <x-primary-button>Tampilkan</x-primary-button>
            </form>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="card-panel p-6">
                    <div class="flex items-center justify-between">
                        <p class="data-label">Total Notaris</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-kumham-100 text-kumham-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </span>
                    </div>
                    <p class="stat-value mt-3">{{ $totalNotaris }}</p>
                    <p class="mt-1 text-sm text-gray-500">Terdaftar seluruh wilayah</p>
                </div>

                @foreach ($stats as $stat)
                    <div class="card-panel p-6">
                        <div class="flex items-center justify-between">
                            <p class="data-label">{{ $stat->name }}</p>
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emas-100 text-emas-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </span>
                        </div>
                        <p class="stat-value mt-3">{{ $stat->reports_count }}</p>
                        <p class="mt-1 text-sm text-gray-500">Laporan masuk {{ \Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }} {{ $year }}</p>
                    </div>
                @endforeach
            </div>

            <div class="card-panel mt-8 overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Laporan Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Notaris</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Wilayah</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Periode</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Akta</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Disahkan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Dibukukan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Wasiat</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Protes</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($recentReports as $report)
                                <tr class="transition hover:bg-kumham-50/50">
                                    <td class="px-6 py-4 font-semibold text-kumham-900">{{ $report->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-kumham-50 px-2.5 py-0.5 text-xs font-semibold text-kumham-700">{{ $report->region?->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM') }} {{ $report->report_year }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_akta) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_disahkan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_dibukukan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_wasiat) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_protes) }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.reports.show', $report) }}" class="font-bold text-kumham-700 hover:text-kumham-500">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">Belum ada laporan masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
