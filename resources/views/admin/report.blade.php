<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">
                <a href="{{ route('admin.reports.index') }}" class="hover:text-kumham-500">&larr; Kembali ke daftar</a>
            </p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Detail Laporan</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 bg-kumham-50/50 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Informasi Notaris &amp; Periode</h3>
                </div>
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2">
                    <div>
                        <p class="data-label">Notaris</p>
                        <p class="mt-1 text-lg font-bold text-kumham-900">{{ $report->user->name }}</p>
                    </div>
                    <div>
                        <p class="data-label">Wilayah</p>
                        <p class="mt-1 font-semibold text-kumham-900">
                            <span class="inline-flex rounded-full bg-kumham-50 px-3 py-1 text-sm text-kumham-700">{{ $report->region?->name ?? '-' }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="data-label">Periode Laporan</p>
                        <p class="mt-1 font-semibold text-kumham-900">{{ \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM') }} {{ $report->report_year }}</p>
                    </div>
                    <div>
                        <p class="data-label">Dikirim Pada</p>
                        <p class="mt-1 font-semibold text-kumham-900">{{ $report->created_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
                    </div>
                </div>
            </div>

            <div class="card-panel mt-6 overflow-hidden">
                <div class="border-b border-gray-200 bg-kumham-50/50 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Rekapitulasi Data</h3>
                </div>
                <div class="grid grid-cols-2 gap-px bg-gray-200 sm:grid-cols-5">
                    @php
                        $items = [
                            ['label' => 'Akta', 'value' => $report->jumlah_akta],
                            ['label' => 'Disahkan', 'value' => $report->jumlah_disahkan],
                            ['label' => 'Dibukukan', 'value' => $report->jumlah_dibukukan],
                            ['label' => 'Wasiat', 'value' => $report->jumlah_wasiat],
                            ['label' => 'Protes', 'value' => $report->jumlah_protes],
                        ];
                    @endphp
                    @foreach ($items as $item)
                        <div class="bg-white p-6 text-center">
                            <p class="text-3xl font-extrabold tabular-nums text-kumham-800">{{ number_format($item['value']) }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $item['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4">
                <a href="{{ route('admin.reports.download', $report) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-emas-400 px-5 py-2.5 text-sm font-bold text-kumham-950 uppercase tracking-wider shadow-card transition duration-200 hover:bg-emas-300 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
