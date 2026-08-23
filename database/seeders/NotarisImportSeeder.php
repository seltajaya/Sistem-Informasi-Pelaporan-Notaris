<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class NotarisImportSeeder extends Seeder
{
    private const EXCEL_PATH = 'storage/app/import/data-notaris.xlsx';

    /**
     * Kabupaten (dinormalisasi) => slug region tujuan.
     */
    private const KAB_TO_REGION = [
        'kota bengkulu' => 'kota-bengkulu',
        'rejang lebong' => 'releparmu',
        'kepahiang' => 'releparmu',
        'lebong' => 'releparmu',
        'seluma' => 'semakuteng',
        'bengkulu selatan' => 'semakuteng',
        'kaur' => 'semakuteng',
        'bengkulu utara' => 'semakuteng',
        'muko muko' => 'semakuteng',
        'bengkulu tengah' => 'semakuteng',
    ];

    public function run(): void
    {
        $path = base_path(self::EXCEL_PATH);
        abort_unless(file_exists($path), 404, "File import tidak ditemukan: {$path}");

        $sheet = IOFactory::load($path)->getSheet(0);

        $regions = Region::pluck('id', 'slug');
        $imported = 0;
        $skipped = [];

        for ($row = 2; $row <= $sheet->getHighestDataRow(); $row++) {
            $name = trim((string) $sheet->getCell("B{$row}")->getValue());
            $npwp = trim((string) $sheet->getCell("E{$row}")->getValue());
            $nik = trim((string) $sheet->getCell("F{$row}")->getValue());
            $kab = trim((string) $sheet->getCell("H{$row}")->getValue());
            $baSumpah = trim((string) $sheet->getCell("S{$row}")->getValue());
            $status = strtolower(trim((string) $sheet->getCell("U{$row}")->getValue()));

            // Filter status: aktif, atau kosong tapi punya No BA Sumpah
            if ($status !== 'aktif' && ! ($status === '' && $baSumpah !== '')) {
                continue;
            }

            if ($name === '') {
                continue;
            }

            // NIK: kolom F, atau fallback 16 digit terakhir dari E (NPWP+NIK tergabung)
            if ($nik === '') {
                $digits = preg_replace('/\D/', '', $npwp);
                $nik = strlen($digits) > 16 ? substr($digits, -16) : '';
            }
            if ($nik === '') {
                $skipped[] = "{$name} (NIK kosong)";

                continue;
            }

            // Region
            $kabNorm = mb_strtolower($kab);
            if (str_starts_with($kabNorm, 'kabupaten ')) {
                $kabNorm = substr($kabNorm, strlen('kabupaten '));
            }
            $kabNorm = trim(preg_replace('/\s+/', ' ', $kabNorm));
            $regionSlug = self::KAB_TO_REGION[$kabNorm] ?? null;
            if ($regionSlug === null || ! $regions->has($regionSlug)) {
                $skipped[] = "{$name} (kabupaten tidak dikenali: '{$kab}')";

                continue;
            }

            User::updateOrCreate(
                ['email' => "notaris.{$nik}@notaris.local"],
                [
                    'name' => $name,
                    'password' => Hash::make('notaris123'),
                    'role' => 'notaris',
                    'region_id' => $regions[$regionSlug],
                    'email_verified_at' => now(),
                ]
            );

            $imported++;
        }

        $this->command?->info("Import selesai: {$imported} notaris.");
        foreach ($skipped as $s) {
            $this->command?->warn("Skip: {$s}");
        }
    }
}
