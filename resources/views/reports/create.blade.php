<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Laporan Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="report_month" :value="__('Bulan')" />
                                <select id="report_month" name="report_month" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
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
                                <x-text-input id="report_year" class="block mt-1 w-full" type="number" name="report_year" min="2000" max="2100" :value="old('report_year', now()->year)" required />
                                <x-input-error :messages="$errors->get('report_year')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jumlah_akta" :value="__('Jumlah Daftar Akta')" />
                                <x-text-input id="jumlah_akta" class="block mt-1 w-full" type="number" min="0" name="jumlah_akta" :value="old('jumlah_akta', 0)" required />
                                <x-input-error :messages="$errors->get('jumlah_akta')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jumlah_disahkan" :value="__('Jumlah Daftar Surat Disahkan (Legalisasi)')" />
                                <x-text-input id="jumlah_disahkan" class="block mt-1 w-full" type="number" min="0" name="jumlah_disahkan" :value="old('jumlah_disahkan', 0)" required />
                                <x-input-error :messages="$errors->get('jumlah_disahkan')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jumlah_dibukukan" :value="__('Jumlah Daftar Surat Dibukukan (Waarmerking)')" />
                                <x-text-input id="jumlah_dibukukan" class="block mt-1 w-full" type="number" min="0" name="jumlah_dibukukan" :value="old('jumlah_dibukukan', 0)" required />
                                <x-input-error :messages="$errors->get('jumlah_dibukukan')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jumlah_wasiat" :value="__('Jumlah Daftar Wasiat')" />
                                <x-text-input id="jumlah_wasiat" class="block mt-1 w-full" type="number" min="0" name="jumlah_wasiat" :value="old('jumlah_wasiat', 0)" required />
                                <x-input-error :messages="$errors->get('jumlah_wasiat')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jumlah_protes" :value="__('Jumlah Daftar Protes')" />
                                <x-text-input id="jumlah_protes" class="block mt-1 w-full" type="number" min="0" name="jumlah_protes" :value="old('jumlah_protes', 0)" required />
                                <x-input-error :messages="$errors->get('jumlah_protes')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="file" :value="__('File Laporan (PDF)')" />
                                <input id="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="file" accept="application/pdf" required />
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 me-4">Batal</a>
                            <x-primary-button>
                                {{ __('Kirim Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
