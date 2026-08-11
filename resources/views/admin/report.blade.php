<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Notaris</dt>
                            <dd class="mt-1 text-sm">{{ $report->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Wilayah</dt>
                            <dd class="mt-1 text-sm">{{ $report->region?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Periode</dt>
                            <dd class="mt-1 text-sm">
                                {{ \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM') }} {{ $report->report_year }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dikirim Pada</dt>
                            <dd class="mt-1 text-sm">{{ $report->created_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-5 gap-4 text-center">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-2xl font-bold">{{ number_format($report->jumlah_akta) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Akta</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-2xl font-bold">{{ number_format($report->jumlah_disahkan) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Disahkan</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-2xl font-bold">{{ number_format($report->jumlah_dibukukan) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Dibukukan</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-2xl font-bold">{{ number_format($report->jumlah_wasiat) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Wasiat</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-2xl font-bold">{{ number_format($report->jumlah_protes) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Protes</div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('admin.reports.download', $report) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
