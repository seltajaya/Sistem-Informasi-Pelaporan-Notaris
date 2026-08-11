<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm flex items-end gap-4">
                <div>
                    <x-input-label for="month" :value="__('Bulan')" />
                    <select id="month" name="month" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-3xl font-bold text-indigo-600">{{ $totalNotaris }}</div>
                        <div class="text-sm text-gray-500 mt-1">Total Notaris Terdaftar</div>
                    </div>
                </div>
                @foreach ($stats as $stat)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-indigo-600">{{ $stat->reports_count }}</div>
                            <div class="text-sm text-gray-500 mt-1">Laporan Masuk — {{ $stat->name }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Laporan Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Notaris</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Akta</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($recentReports as $report)
                                    <tr>
                                        <td class="px-4 py-3">{{ $report->user->name }}</td>
                                        <td class="px-4 py-3">{{ $report->region?->name ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            {{ \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM') }} {{ $report->report_year }}
                                        </td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_akta) }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
