<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">
                <a href="{{ route('admin.recap.annual', ['region_id' => request('region_id')]) }}" class="hover:text-kumham-500">&larr; Kembali ke Tahunan</a>
            </p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Rekapitulasi Bulanan {{ $year }}</h2>
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
                    <h3 class="font-bold text-kumham-900">Data Bulanan — Tahun {{ $year }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Bulan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Laporan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Akta</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Disahkan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Dibukukan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Wasiat</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Protes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($months as $row)
                                <tr class="transition hover:bg-kumham-50/50">
                                    <td class="px-6 py-4 font-bold text-kumham-900">{{ $monthsNames[$row->report_month] }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ $row->total_laporan }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-kumham-900">{{ number_format($row->total_akta) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_disahkan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_dibukukan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_wasiat) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($row->total_protes) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada data untuk tahun {{ $year }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
