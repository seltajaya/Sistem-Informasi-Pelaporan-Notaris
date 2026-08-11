<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm flex flex-wrap items-end gap-4">
                <div>
                    <x-input-label for="q" :value="__('Cari Notaris')" />
                    <x-text-input id="q" class="mt-1" type="text" name="q" :value="request('q')" placeholder="Nama notaris..." />
                </div>
                <div>
                    <x-input-label for="region_id" :value="__('Wilayah')" />
                    <select id="region_id" name="region_id" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="month" :value="__('Bulan')" />
                    <select id="month" name="month" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                    <x-text-input id="year" class="mt-1" type="number" name="year" min="2000" max="2100" :value="request('year')" />
                </div>
                <div class="flex items-center gap-2">
                    <x-primary-button>Filter</x-primary-button>
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reset</a>
                </div>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Notaris</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Akta</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Disahkan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Dibukukan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Wasiat</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Protes</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reports as $report)
                                    <tr>
                                        <td class="px-4 py-3">{{ $report->user->name }}</td>
                                        <td class="px-4 py-3">{{ $report->region?->name ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            {{ \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM') }} {{ $report->report_year }}
                                        </td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_akta) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_disahkan) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_dibukukan) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_wasiat) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_protes) }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('admin.reports.download', $report) }}" class="text-indigo-600 hover:text-indigo-900">PDF</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">Tidak ada laporan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
