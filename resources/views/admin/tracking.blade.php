<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Tracking Kepatuhan</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Notaris Belum Melapor</h2>
            <p class="mt-1 text-sm text-gray-500">Bandingkan notaris terdaftar dengan laporan masuk pada periode tertentu.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <form method="GET" class="card-panel mb-6 flex flex-wrap items-end gap-4 p-4">
                <div>
                    <x-input-label for="region_id" :value="__('Wilayah')" />
                    <select id="region_id" name="region_id" class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="month" :value="__('Bulan')" />
                    <select id="month" name="month" class="mt-1 rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @selected(request('month', now()->month) == $i)>
                                {{ $monthsNames[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <x-input-label for="year" :value="__('Tahun')" />
                    <x-text-input id="year" class="mt-1" type="number" name="year" min="2000" max="2100" :value="request('year', now()->year)" />
                </div>
                <x-primary-button>Cek Kepatuhan</x-primary-button>
            </form>

            @if (request('region_id'))
                <div class="card-panel overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 flex flex-wrap items-center justify-between gap-2">
                        <h3 class="font-bold text-kumham-900">
                            Belum Lapor — {{ $monthsNames[(int) request('month', now()->month)] }} {{ request('year', now()->year) }}
                        </h3>
                        <span class="inline-flex rounded-full bg-kumham-50 px-3 py-1 text-xs font-bold text-kumham-700">
                            {{ $regions->firstWhere('id', (int) request('region_id'))?->name }}
                        </span>
                    </div>

                    @if ($missing->isEmpty())
                        <div class="flex flex-col items-center px-6 py-14 text-center">
                            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-50">
                                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                            <p class="mt-4 font-bold text-kumham-900">Semua notaris sudah melapor</p>
                            <p class="mt-1 text-sm text-gray-500">Kepatuhan wilayah ini tercapai pada periode tersebut.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-kumham-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">#</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Nama Notaris</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Email</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($missing as $index => $notaris)
                                        <tr class="transition hover:bg-red-50/40">
                                            <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 font-semibold text-kumham-900">{{ $notaris->name }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $notaris->email }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Belum Lapor
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @else
                <div class="card-panel flex flex-col items-center px-6 py-16 text-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-kumham-50">
                        <svg class="h-8 w-8 text-kumham-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <p class="mt-4 font-bold text-kumham-900">Pilih wilayah untuk memulai</p>
                    <p class="mt-1 text-sm text-gray-500">Pilih wilayah dan periode, lalu sistem akan membandingkannya dengan laporan yang masuk.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
