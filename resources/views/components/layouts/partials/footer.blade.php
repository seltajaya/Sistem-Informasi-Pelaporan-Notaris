<footer class="bg-kumham-950 text-white mt-auto">
    <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row items-start justify-between gap-8">
            <div class="max-w-md">
                <x-layouts.partials.logo dark compact />
                <p class="mt-4 text-sm leading-relaxed text-white/60">
                    Sistem Informasi Pelaporan Notaris digunakan oleh notaris di lingkungan
                    Kantor Wilayah Kementerian Hukum dan HAM Bengkulu untuk menyampaikan laporan
                    bulanan dan tahunan secara digital.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-sm">
                <div>
                    <p class="mb-3 font-bold uppercase tracking-wider text-emas-300">Kontak</p>
                    <ul class="space-y-2 text-white/70">
                        <li>Jalan A. Yani No. 2, Kota Bengkulu</li>
                        <li>(0736) 21654</li>
                        <li>kanwil.bengkulu@kemenkumham.go.id</li>
                    </ul>
                </div>
                <div>
                    <p class="mb-3 font-bold uppercase tracking-wider text-emas-300">Pelayanan</p>
                    <ul class="space-y-2 text-white/70">
                        <li>SIMAKUTENG · RELEPARMU · KOTA BENGKULU</li>
                        <li>Administrasi Hukum Umum</li>
                        <li>Bantuan Hukum</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/50">
            <p>&copy; {{ now()->year }} Kantor Wilayah Kementerian Hukum dan HAM Bengkulu</p>
            <p>Kementerian Hukum dan Hak Asasi Manusia Republik Indonesia</p>
        </div>
    </div>
</footer>
