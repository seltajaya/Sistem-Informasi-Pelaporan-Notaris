<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotarisImportSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_import_creates_active_notaris_from_excel(): void
    {
        $before = User::where('role', 'notaris')->count();

        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);

        $after = User::where('role', 'notaris')->count();
        $this->assertGreaterThan($before, $after);
        // 135 notaris unik dari Excel (116 aktif - 1 duplikat NIK + 20 status kosong ber-BA) + 2 notaris seed lama
        $this->assertSame(135 + $before, $after);
    }

    public function test_import_skips_notaris_belum_aktif_and_empty_rows(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);

        // Melly Novianti (baris kosong total) tidak masuk
        $this->assertDatabaseMissing('users', ['name' => 'Melly Novianti']);
    }

    public function test_email_generated_from_nik(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);

        $this->assertDatabaseHas('users', [
            'name' => 'A.RAMALI POMPIDO',
            'email' => 'notaris.1702192808720003@notaris.local',
            'role' => 'notaris',
        ]);
    }

    public function test_region_mapping_kota_bengkulu(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);

        $kotaBengkuluId = Region::where('slug', 'kota-bengkulu')->value('id');
        $notaris = User::where('email', 'notaris.1771062306790001@notaris.local')->first(); // DENI YOHANES

        $this->assertNotNull($notaris);
        $this->assertSame($kotaBengkuluId, $notaris->region_id);
    }

    public function test_region_mapping_releparmu(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);

        $releparmuId = Region::where('slug', 'releparmu')->value('id');
        $notaris = User::where('email', 'notaris.1702192808720003@notaris.local')->first(); // A.RAMALI (REJANG LEBONG)

        $this->assertNotNull($notaris);
        $this->assertSame($releparmuId, $notaris->region_id);
    }

    public function test_nik_taken_from_npwp_column_when_nik_missing(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);

        // APRIANI ASTUTI: NIK tergabung di kolom NPWP -> ambil 16 digit terakhir
        $this->assertDatabaseHas('users', [
            'email' => 'notaris.1771026404860013@notaris.local',
            'role' => 'notaris',
        ]);
    }

    public function test_duplicate_notaris_only_imported_once(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);
        $count = User::where('role', 'notaris')
            ->where('name', 'like', '%ANIKA DEWI%')
            ->count();

        $this->assertSame(1, $count);
    }

    public function test_import_is_idempotent(): void
    {
        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);
        $first = User::where('role', 'notaris')->count();

        $this->artisan('db:seed', ['--class' => \Database\Seeders\NotarisImportSeeder::class]);
        $second = User::where('role', 'notaris')->count();

        $this->assertSame($first, $second);
    }
}
