<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ReportFlowTest extends TestCase
{
    use RefreshDatabase;

    private function region(): Region
    {
        return Region::create(['name' => 'MPD 1']);
    }

    public function test_notaris_can_submit_report_and_admin_sees_it(): void
    {
        $region = $this->region();
        $notaris = User::create([
            'name' => 'Notaris A',
            'email' => 'a@test.com',
            'password' => bcrypt('password'),
            'role' => 'notaris',
            'region_id' => $region->id,
        ]);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($notaris)->post('/laporan', [
            'report_month' => 8,
            'report_year' => 2026,
            'jumlah_akta' => 10,
            'jumlah_disahkan' => 5,
            'jumlah_dibukukan' => 3,
            'jumlah_wasiat' => 2,
            'jumlah_protes' => 1,
            'file' => UploadedFile::fake()->create('laporan.pdf', 10, 'application/pdf'),
        ]);

        $response->assertRedirect(route('dashboard'));

        $report = Report::first();
        $this->assertSame(8, $report->report_month);
        $this->assertSame($region->id, $report->region_id);

        $this->actingAs($admin)
            ->get('/admin/laporan')
            ->assertSee('Notaris A');
    }

    public function test_duplicate_report_for_same_month_is_rejected(): void
    {
        $notaris = User::create([
            'name' => 'Notaris B',
            'email' => 'b@test.com',
            'password' => bcrypt('password'),
            'role' => 'notaris',
            'region_id' => $this->region()->id,
        ]);

        $payload = [
            'report_month' => 8,
            'report_year' => 2026,
            'jumlah_akta' => 10,
            'jumlah_disahkan' => 0,
            'jumlah_dibukukan' => 0,
            'jumlah_wasiat' => 0,
            'jumlah_protes' => 0,
            'file' => UploadedFile::fake()->create('laporan.pdf', 10, 'application/pdf'),
        ];

        $this->actingAs($notaris)->post('/laporan', $payload)->assertRedirect(route('dashboard'));
        $this->actingAs($notaris)->post('/laporan', $payload)->assertSessionHasErrors('report_month');
        $this->assertSame(1, Report::count());
    }

    public function test_tracking_lists_notaris_who_did_not_submit(): void
    {
        $region = $this->region();
        $submitted = User::create([
            'name' => 'Sudah Lapor',
            'email' => 's@test.com',
            'password' => bcrypt('password'),
            'role' => 'notaris',
            'region_id' => $region->id,
        ]);
        $missing = User::create([
            'name' => 'Belum Lapor',
            'email' => 'm@test.com',
            'password' => bcrypt('password'),
            'role' => 'notaris',
            'region_id' => $region->id,
        ]);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        Report::create([
            'user_id' => $submitted->id,
            'region_id' => $region->id,
            'report_month' => 8,
            'report_year' => 2026,
            'file_path' => 'reports/x.pdf',
        ]);

        $this->actingAs($admin)
            ->get('/admin/kepatuhan?region_id='.$region->id.'&month=8&year=2026')
            ->assertSee('Belum Lapor')
            ->assertDontSee('Sudah Lapor');
    }

    public function test_notaris_cannot_access_admin_pages(): void
    {
        $notaris = User::create([
            'name' => 'Notaris C',
            'email' => 'c@test.com',
            'password' => bcrypt('password'),
            'role' => 'notaris',
            'region_id' => $this->region()->id,
        ]);

        $this->actingAs($notaris)->get('/admin')->assertForbidden();
    }
}