<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_wilayah_only_sees_own_region_reports(): void
    {
        $regionB = Region::where('slug', 'kota-bengkulu')->first();
        $notarisB = User::where('email', 'notaris2@example.com')->first();

        Report::create([
            'user_id' => $notarisB->id,
            'region_id' => $regionB->id,
            'report_month' => 8,
            'report_year' => 2026,
            'file_path' => 'reports/x.pdf',
        ]);

        $admin = User::where('email', 'adm.simakuteng@example.com')->first();

        $this->actingAs($admin)
            ->get('/admin/laporan')
            ->assertDontSee('Notaris Kota Bengkulu');
    }
}