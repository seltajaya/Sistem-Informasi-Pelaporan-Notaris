<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_notaris_login_from_wrong_region_is_rejected(): void
    {
        $notaris = User::where('email', 'notaris1@example.com')->first(); // SIMAKUTENG
        $this->assertSame('simakuteng', Region::find($notaris->region_id)->slug);

        $response = $this->post('/login/kota-bengkulu', [
            'email' => 'notaris1@example.com',
            'password' => 'notaris123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_notaris_login_from_own_region_succeeds(): void
    {
        $this->post('/login/simakuteng', [
            'email' => 'notaris1@example.com',
            'password' => 'notaris123',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_superadmin_login_via_admin_login_succeeds(): void
    {
        $this->get('/admin/login')->assertOk();

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ])->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_region_login_page_shows_region_badge(): void
    {
        $this->get('/login/simakuteng')->assertSee('SIMAKUTENG');
    }
}