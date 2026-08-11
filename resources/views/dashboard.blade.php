<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-kumham-700">Dashboard Notaris</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">
                    Halo, {{ auth()->user()->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Wilayah penempatan: <span class="font-semibold text-kumham-700">{{ auth()->user()->region?->name ?? '-' }}</span>
                </p>
            </div>
            <a href="{{ route('reports.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-kumham-700 px-5 py-2.5 text-sm font-bold text-white uppercase tracking-wider shadow-card transition duration-200 hover:bg-kumham-600 active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Riwayat Laporan</h3>
                    <p class="text-sm text-gray-500">Seluruh laporan bulanan dan tahunan yang pernah Anda kirim.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Periode</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Akta</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Disahkan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Dibukukan</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Wasiat</th>
                                <th class="px-6 py-3 text-right font-semibold uppercase tracking-wider text-kumham-800">Protes</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">File</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($reports as $report)
                                <tr class="transition hover:bg-kumham-50/50">
                                    <td class="px-6 py-4 font-semibold text-kumham-900">
                                        <span class="inline-flex items-center gap-2">
                                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-kumham-100 text-kumham-700">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </span>
                                            {{ \Carbon\Carbon::create()->month($report->report_month)->locale('id')->isoFormat('MMMM') }} {{ $report->report_year }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_akta) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_disahkan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_dibukukan) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_wasiat) }}</td>
                                    <td class="px-6 py-4 text-right font-medium">{{ number_format($report->jumlah_protes) }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('reports.download', $report) }}"
                                            class="inline-flex items-center gap-1.5 rounded-md bg-emas-100 px-3 py-1.5 text-xs font-bold text-kumham-800 transition hover:bg-emas-200">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                            PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-kumham-50">
                                            <svg class="h-8 w-8 text-kumham-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <p class="mt-4 font-semibold text-kumham-900">Belum ada laporan</p>
                                        <p class="mt-1 text-sm text-gray-500">Kirim laporan bulanan pertama Anda melalui tombol "Laporan Baru".</p>
                                    </td>
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
