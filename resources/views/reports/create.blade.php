<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Input Laporan</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Laporan Bulanan Notaris</h2>
            <p class="mt-1 text-sm text-gray-500">Lengkapi jumlah akta dan unggah file laporan PDF.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="card-panel overflow-hidden">
                    <div class="border-b border-gray-200 bg-kumham-50/50 px-6 py-4">
                        <h3 class="font-bold text-kumham-900">Periode Laporan</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                        <div>
                            <x-input-label for="report_month" :value="__('Bulan')" />
                            <select id="report_month" name="report_month" class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @selected(old('report_month', now()->month) == $i)>
                                        {{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('report_month')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="report_year" :value="__('Tahun')" />
                            <x-text-input id="report_year" class="mt-2 block w-full" type="number" name="report_year" min="2000" max="2100" :value="old('report_year', now()->year)" required />
                            <x-input-error :messages="$errors->get('report_year')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="card-panel mt-6 overflow-hidden">
                    <div class="border-b border-gray-200 bg-kumham-50/50 px-6 py-4">
                        <h3 class="font-bold text-kumham-900">Jumlah Daftar Akta &amp; Dokumen</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                        <div>
                            <x-input-label for="jumlah_akta" :value="__('Jumlah Daftar Akta')" />
                            <x-text-input id="jumlah_akta" class="mt-2 block w-full" type="number" min="0" name="jumlah_akta" :value="old('jumlah_akta', 0)" required />
                            <x-input-error :messages="$errors->get('jumlah_akta')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="jumlah_disahkan" :value="__('Surat Disahkan (Legalisasi)')" />
                            <x-text-input id="jumlah_disahkan" class="mt-2 block w-full" type="number" min="0" name="jumlah_disahkan" :value="old('jumlah_disahkan', 0)" required />
                            <x-input-error :messages="$errors->get('jumlah_disahkan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="jumlah_dibukukan" :value="__('Surat Dibukukan (Waarmerking)')" />
                            <x-text-input id="jumlah_dibukukan" class="mt-2 block w-full" type="number" min="0" name="jumlah_dibukukan" :value="old('jumlah_dibukukan', 0)" required />
                            <x-input-error :messages="$errors->get('jumlah_dibukukan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="jumlah_wasiat" :value="__('Jumlah Daftar Wasiat')" />
                            <x-text-input id="jumlah_wasiat" class="mt-2 block w-full" type="number" min="0" name="jumlah_wasiat" :value="old('jumlah_wasiat', 0)" required />
                            <x-input-error :messages="$errors->get('jumlah_wasiat')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="jumlah_protes" :value="__('Jumlah Daftar Protes')" />
                            <x-text-input id="jumlah_protes" class="mt-2 block w-full" type="number" min="0" name="jumlah_protes" :value="old('jumlah_protes', 0)" required />
                            <x-input-error :messages="$errors->get('jumlah_protes')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="file" :value="__('File Laporan (PDF)')" />
                            <input id="file"
                                class="mt-2 block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-500 shadow-sm file:mr-4 file:cursor-pointer file:rounded-l-lg file:border-0 file:bg-kumham-700 file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white hover:file:bg-kumham-600 focus:outline-none focus:ring-2 focus:ring-kumham-500"
                                type="file" name="file" accept="application/pdf" required />
                            <p class="mt-2 text-xs text-gray-500">Format PDF, maksimal 10 MB.</p>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-kumham-700">Batal</a>
                    <x-primary-button>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Kirim Laporan
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
