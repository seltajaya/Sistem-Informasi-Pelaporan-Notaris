<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tracking Kepatuhan Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm flex flex-wrap items-end gap-4">
                <div>
                    <x-input-label for="region_id" :value="__('Wilayah')" />
                    <select id="region_id" name="region_id" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="month" :value="__('Bulan')" />
                    <select id="month" name="month" class="mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">
                            Notaris Belum Lapor — {{ $monthsNames[(int) request('month', now()->month)] }} {{ request('year', now()->year) }}
                        </h3>

                        @if ($missing->isEmpty())
                            <p class="text-green-600 text-sm">Semua notaris di wilayah ini sudah mengirim laporan.</p>
                        @else
                            <div class="overflow-x-auto mt-4">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Notaris</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($missing as $index => $notaris)
                                            <tr>
                                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 font-medium">{{ $notaris->name }}</td>
                                                <td class="px-4 py-3">{{ $notaris->email }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Belum Lapor</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
