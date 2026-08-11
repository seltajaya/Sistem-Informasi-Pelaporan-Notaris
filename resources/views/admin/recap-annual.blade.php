<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekapitulasi Tahunan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm flex items-end gap-4">
                <div>
                    <x-input-label for="region_id" :value="__('Wilayah')" />
                    <select id="region_id" name="region_id" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Wilayah</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button>Filter</x-primary-button>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Laporan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Akta</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Disahkan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Dibukukan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Wasiat</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Protes</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Rincian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($years as $row)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold">{{ $row->report_year }}</td>
                                        <td class="px-4 py-3 text-right">{{ $row->total_laporan }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($row->total_akta) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($row->total_disahkan) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($row->total_dibukukan) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($row->total_wasiat) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($row->total_protes) }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.recap.monthly', ['year' => $row->report_year, 'region_id' => request('region_id')]) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Per Bulan</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada data rekapitulasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
