<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Rekapitulasi</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Rekapitulasi Tahunan</h2>
            <p class="mt-1 text-sm text-gray-500">Total akta dan dokumen per tahun. Klik tahun untuk melihat rincian bulanan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
            <form method="GET" class="card-panel mb-6 flex flex-wrap items-end gap-4 p-4">
                <div>
                    <x-input-label for="region_id" :value="__('Wilayah')" />
                    <select id="region_id" name="region_id" class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                        <option value="">Semua Wilayah</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button>Filter</x-primary-button>
            </form>

            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Data Tahunan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Tahun</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Laporan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Akta</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Disahkan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Dibukukan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Wasiat</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Protes</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Rincian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($years as $row)
                                <tr class="transition hover:bg-kumham-50/50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.recap.monthly', ['year' => $row->report_year, 'region_id' => request('region_id')]) }}"
                                            class="inline-flex items-center gap-2 font-extrabold text-kumham-700 hover:text-kumham-500">
                                            {{ $row->report_year }}
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium">{{ $row->total_laporan }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-kumham-900">{{ number_format($row->total_akta) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_disahkan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_dibukukan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_wasiat) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_protes) }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.recap.monthly', ['year' => $row->report_year, 'region_id' => request('region_id')]) }}"
                                            class="inline-flex items-center gap-1 rounded-md bg-kumham-50 px-2.5 py-1.5 text-xs font-bold text-kumham-700 transition hover:bg-kumham-100">
                                            Per Bulan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">Belum ada data rekapitulasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
