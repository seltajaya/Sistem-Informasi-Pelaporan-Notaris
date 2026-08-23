<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function notarisA(): User
    {
        return User::where('email', 'notaris1@example.com')->first(); // SEMAKUTENG
    }

    private function notarisB(): User
    {
        return User::where('email', 'notaris2@example.com')->first(); // KOTA BENGKULU
    }

    private function adminWilayah(): User
    {
        return User::where('email', 'adm.semakuteng@example.com')->first();
    }

    private function superAdmin(): User
    {
        return User::where('email', 'admin@example.com')->first();
    }

    /** @test */
    public function guest_is_redirected_from_protected_pages(): void
    {
        foreach (['/dashboard', '/laporan/create', '/admin', '/admin/notaris', '/admin/kepatuhan', '/profile'] as $url) {
            $this->get($url)->assertRedirect(route('login', absolute: false));
        }
        $this->assertGuest();
    }

    /** @test */
    public function notaris_cannot_access_admin_pages(): void
    {
        $adminUrls = [
            '/admin',
            '/admin/laporan',
            '/admin/rekapitulasi',
            '/admin/kepatuhan',
            '/admin/admin-wilayah',
            '/admin/notaris',
        ];

        foreach ($adminUrls as $url) {
            $this->actingAs($this->notarisA())->get($url)->assertForbidden();
        }

        // POST endpoints juga harus ditolak
        $this->actingAs($this->notarisA())
            ->post('/admin/notaris', ['name' => 'X', 'email' => 'x@x.com', 'password' => 'password123'])
            ->assertForbidden();
    }

    /** @test */
    public function report_submission_is_notaris_only(): void
    {
        // Admin wilayah & superadmin tidak boleh membuat laporan
        $payload = [
            'report_month' => 1,
            'report_year' => 2026,
            'jumlah_akta' => 1,
            'jumlah_disahkan' => 0,
            'jumlah_dibukukan' => 0,
            'jumlah_wasiat' => 0,
            'jumlah_protes' => 0,
            'file' => UploadedFile::fake()->create('laporan.pdf', 10, 'application/pdf'),
        ];

        $this->actingAs($this->adminWilayah())
            ->post('/laporan', $payload)
            ->assertForbidden();

        $this->actingAs($this->superAdmin())
            ->post('/laporan', $payload)
            ->assertForbidden();

        $this->assertSame(0, Report::count());
    }

    /** @test */
    public function notaris_cannot_download_other_notaris_report(): void
    {
        $report = Report::create([
            'user_id' => $this->notarisB()->id,
            'region_id' => $this->notarisB()->region_id,
            'report_month' => 1,
            'report_year' => 2026,
            'file_path' => 'reports/x.pdf',
        ]);

        // Guest tidak bisa (uji dulu sebelum actingAs, karena auth persisten dalam satu test)
        $this->get("/laporan/{$report->id}/download")
            ->assertRedirect(route('login', absolute: false));

        // Notaris lain tidak bisa
        $this->actingAs($this->notarisA())
            ->get("/laporan/{$report->id}/download")
            ->assertForbidden();
    }

    /** @test */
    public function profile_update_ignores_role_and_region_mass_assignment(): void
    {
        $notaris = $this->notarisA();
        $originalRole = $notaris->role;

        $this->actingAs($notaris)->patch('/profile', [
            'name' => 'Nama Baru',
            'email' => $notaris->email,
            'role' => 'superadmin',
            'region_id' => Region::where('slug', 'kota-bengkulu')->value('id'),
        ]);

        $notaris->refresh();
        $this->assertSame('Nama Baru', $notaris->name);
        $this->assertSame($originalRole, $notaris->role);
    }

    /** @test */
    public function report_upload_rejects_non_pdf(): void
    {
        $payload = [
            'report_month' => 2,
            'report_year' => 2026,
            'jumlah_akta' => 1,
            'jumlah_disahkan' => 0,
            'jumlah_dibukukan' => 0,
            'jumlah_wasiat' => 0,
            'jumlah_protes' => 0,
            'file' => UploadedFile::fake()->create('evil.html', 10, 'text/html'),
        ];

        $this->actingAs($this->notarisA())
            ->post('/laporan', $payload)
            ->assertSessionHasErrors('file');

        $this->assertSame(0, Report::count());
    }

    /** @test */
    public function superadmin_registering_notaris_requires_valid_region(): void
    {
        $response = $this->actingAs($this->superAdmin())->post('/admin/notaris', [
            'name' => 'Notaris Tanpa Wilayah',
            'email' => 'tanpa.wilayah@test.com',
            'password' => 'password123',
            'region_id' => 99999, // tidak ada
        ]);

        $response->assertSessionHasErrors('region_id');
        $this->assertDatabaseMissing('users', ['email' => 'tanpa.wilayah@test.com']);
    }

    /** @test */
    public function duplicate_email_on_notaris_registration_is_rejected(): void
    {
        $existing = User::where('role', 'notaris')->first();

        $this->actingAs($this->adminWilayah())
            ->post('/admin/notaris', [
                'name' => 'Duplikat',
                'email' => $existing->email,
                'password' => 'password123',
            ])
            ->assertSessionHasErrors('email');
    }
}
