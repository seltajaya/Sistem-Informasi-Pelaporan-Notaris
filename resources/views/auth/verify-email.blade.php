<x-guest-layout>
    <x-auth-card title="Verifikasi Email" subtitle="Verifikasi alamat email Anda untuk melanjutkan.">
        <p class="text-sm text-gray-600">
            Tautan verifikasi telah dikirim ke email Anda. Jika belum menerima, Anda dapat meminta tautan baru.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                Tautan verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>Kirim Ulang Email</x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-gray-600 hover:text-kumham-700">Keluar</button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>
