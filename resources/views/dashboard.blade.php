<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold">Halo, {{ auth()->user()->name }}</h3>
                            <p class="text-sm text-gray-500">Wilayah: {{ auth()->user()->region?->name ?? '-' }}</p>
                        </div>
                        <a href="{{ route('reports.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            + Laporan Baru
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Akta</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Disahkan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Dibukukan</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Wasiat</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Protes</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">File</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reports as $report)
                                    <tr>
                                        <td class="px-4 py-3">
                                            @php($bulan = \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM'))
                                            {{ $bulan }} {{ $report->report_year }}
                                        </td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_akta) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_disahkan) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_dibukukan) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_wasiat) }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($report->jumlah_protes) }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('reports.download', $report) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Download PDF</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                            Belum ada laporan. Klik "Laporan Baru" untuk mengirim laporan pertama Anda.
                                        </td>
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
