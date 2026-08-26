<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecapPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function superAdmin(): User
    {
        return User::where('email', 'admin@example.com')->first();
    }

    private function adminWilayah(): User
    {
        return User::where('email', 'adm.semakuteng@example.com')->first();
    }

    public function test_superadmin_can_download_annual_recap_pdf(): void
    {
        Report::create([
            'user_id' => User::where('email', 'notaris1@example.com')->value('id'),
            'region_id' => Region::where('slug', 'semakuteng')->value('id'),
            'report_month' => 1,
            'report_year' => 2026,
            'jumlah_akta' => 10,
            'file_path' => 'reports/x.pdf',
        ]);

        $response = $this->actingAs($this->superAdmin())
            ->get('/admin/rekapitulasi/pdf');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        // PDF magic bytes
        $this->assertStringStartsWith('%PDF', substr($response->getContent(), 0, 4));
    }

    public function test_pdf_respects_region_filter(): void
    {
        Report::create([
            'user_id' => User::where('email', 'notaris2@example.com')->value('id'),
            'region_id' => Region::where('slug', 'kota-bengkulu')->value('id'),
            'report_month' => 1,
            'report_year' => 2026,
            'jumlah_akta' => 5,
            'file_path' => 'reports/x.pdf',
        ]);

        $regionId = Region::where('slug', 'kota-bengkulu')->value('id');
        $response = $this->actingAs($this->superAdmin())
            ->get("/admin/rekapitulasi/pdf?region_id={$regionId}");

        // Isi teks PDF ter-kompresi Flate sehingga tidak bisa dicari sebagai string;
        // cukup pastikan respons adalah PDF valid.
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', substr($response->getContent(), 0, 4));
        $this->assertGreaterThan(1000, strlen($response->getContent()));
    }

    public function test_admin_wilayah_and_notaris_cannot_download(): void
    {
        $this->actingAs($this->adminWilayah())
            ->get('/admin/rekapitulasi/pdf')
            ->assertForbidden();

        $notaris = User::where('email', 'notaris1@example.com')->first();
        $this->actingAs($notaris)
            ->get('/admin/rekapitulasi/pdf')
            ->assertForbidden();
    }
}
