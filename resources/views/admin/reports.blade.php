<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Manajemen Laporan</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Data Laporan Notaris</h2>
            <p class="mt-1 text-sm text-gray-500">Cari dan unduh laporan yang dikirim notaris.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
            <form method="GET" class="card-panel mb-6 flex flex-wrap items-end gap-4 p-4">
                <div>
                    <x-input-label for="q" :value="__('Cari Notaris')" />
                    <x-text-input id="q" class="mt-1" type="text" name="q" :value="request('q')" placeholder="Nama notaris..." />
                </div>
                @if ($canSelectRegion ?? true)
                <div>
                    <x-input-label for="region_id" :value="__('Wilayah')" />
                    <select id="region_id" name="region_id" class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                        <option value="">Semua</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <x-input-label for="month" :value="__('Bulan')" />
                    <select id="month" name="month" class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                        <option value="">Semua</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @selected(request('month') == $i)>
                                {{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <x-input-label for="year" :value="__('Tahun')" />
                    <x-text-input id="year" class="mt-1" type="number" name="year" min="2000" max="2100" :value="request('year')" placeholder="Semua" />
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button>Filter</x-primary-button>
                    <a href="{{ route('admin.reports.index') }}" class="text-sm font-semibold text-gray-600 hover:text-kumham-700">Reset</a>
                </div>
            </form>

            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="font-bold text-kumham-900">Daftar Laporan</h3>
                    <span class="text-sm text-gray-500">{{ $reports->total() }} laporan</span>
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
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($reports as $report)
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
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center gap-1 rounded-md bg-kumham-50 px-2.5 py-1.5 text-xs font-bold text-kumham-700 transition hover:bg-kumham-100">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.reports.download', $report) }}" class="inline-flex items-center gap-1 rounded-md bg-emas-100 px-2.5 py-1.5 text-xs font-bold text-kumham-800 transition hover:bg-emas-200">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">Tidak ada laporan yang cocok dengan filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reports->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
