<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dashboard_shows_compliance_counts_for_current_month(): void
    {
        $region = Region::where('slug', 'simakuteng')->first();
        $submitted = User::where('email', 'notaris1@example.com')->first();
        $missing = User::create([
            'name' => 'Notaris Belum',
            'email' => 'belum@test.com',
            'password' => bcrypt('password'),
            'role' => 'notaris',
            'region_id' => $region->id,
        ]);

        Report::create([
            'user_id' => $submitted->id,
            'region_id' => $region->id,
            'report_month' => now()->month,
            'report_year' => now()->year,
            'file_path' => 'reports/x.pdf',
        ]);

        $this->actingAs($submitted)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Sudah Melapor')
            ->assertSee('Belum Melapor')
            ->assertSee('Notaris Belum');
    }

    public function test_dashboard_only_counts_same_region_notaris(): void
    {
        $regionSimak = Region::where('slug', 'simakuteng')->first();
        $regionKota = Region::where('slug', 'kota-bengkulu')->first();

        $notarisKota = User::where('email', 'notaris2@example.com')->first();

        Report::create([
            'user_id' => $notarisKota->id,
            'region_id' => $regionKota->id,
            'report_month' => now()->month,
            'report_year' => now()->year,
            'file_path' => 'reports/x.pdf',
        ]);

        $notarisSimak = User::where('email', 'notaris1@example.com')->first();

        $response = $this->actingAs($notarisSimak)->get(route('dashboard'));
        $response->assertOk();

        $response->assertViewHas('sudahMelapor', 0);
        $response->assertViewHas('daftarBelum', function ($list) {
            return $list->pluck('id')->all() === [User::where('email', 'notaris1@example.com')->value('id')];
        });
    }

    public function test_dashboard_forbidden_for_superadmin(): void
    {
        $superadmin = User::where('email', 'admin@example.com')->first();

        $this->actingAs($superadmin)
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
