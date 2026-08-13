<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Pendaftaran</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Daftarkan Notaris</h2>
            <p class="mt-1 text-sm text-gray-500">Buat akun notaris peserta pelaporan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">
            <div class="card-panel overflow-hidden lg:col-span-2">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Daftar Notaris</h3>
                </div>
                @if (session('status'))
                    <div class="mx-6 mt-4 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Email</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Wilayah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($notaris as $n)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-kumham-900">{{ $n->name }}</td>
                                    <td class="px-6 py-4">{{ $n->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-kumham-50 px-2.5 py-0.5 text-xs font-semibold text-kumham-700">{{ $n->region?->name }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">Belum ada notaris terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Tambah Notaris</h3>
                </div>
                <form method="POST" action="{{ route('admin.notaris.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password" :value="__('Password Awal')" />
                        <x-text-input id="password" class="mt-1 block w-full" type="text" name="password" value="notaris123" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    @if ($canSelectRegion)
                        <div>
                            <x-input-label for="region_id" :value="__('Wilayah')" />
                            <select id="region_id" name="region_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('region_id')" class="mt-2" />
                        </div>
                    @endif
                    <x-primary-button>Daftarkan</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>