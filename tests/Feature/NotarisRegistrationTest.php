<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotarisRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_wilayah_can_register_notaris_in_own_region(): void
    {
        $admin = User::where('email', 'adm.semakuteng@example.com')->first();
        $region = Region::where('slug', 'semakuteng')->first();

        $response = $this->actingAs($admin)->post('/admin/notaris', [
            'name' => 'Notaris Baru',
            'email' => 'baru@test.com',
            'password' => 'notaris123',
        ]);

        $response->assertRedirect(route('admin.notaris.index'));
        $this->assertDatabaseHas('users', ['email' => 'baru@test.com', 'role' => 'notaris', 'region_id' => $region->id]);
    }

    public function test_superadmin_can_create_region_admin(): void
    {
        $superadmin = User::where('email', 'admin@example.com')->first();
        $region = Region::where('slug', 'releparmu')->first();

        $this->actingAs($superadmin)->post('/admin/admin-wilayah', [
            'name' => 'Admin Releparmu 2',
            'email' => 'adm2.releparmu@example.com',
            'password' => 'admin123',
            'region_id' => $region->id,
        ])->assertRedirect(route('admin.region-admins.index'));

        $this->assertDatabaseHas('users', ['email' => 'adm2.releparmu@example.com', 'role' => 'admin_wilayah', 'region_id' => $region->id]);
    }

    public function test_admin_wilayah_cannot_register_notaris_outside_own_region(): void
    {
        $admin = User::where('email', 'adm.semakuteng@example.com')->first();

        $this->actingAs($admin)->post('/admin/notaris', [
            'name' => 'X',
            'email' => 'x@test.com',
            'password' => 'notaris123',
        ]);

        $created = User::where('email', 'x@test.com')->first();
        $this->assertSame(Region::where('slug', 'semakuteng')->first()->id, $created->region_id);
    }

    public function test_public_register_page_is_gone(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_admin_wilayah_cannot_access_superadmin_pages(): void
    {
        $admin = User::where('email', 'adm.semakuteng@example.com')->first();

        $this->actingAs($admin)->get('/admin/admin-wilayah')->assertForbidden();
    }

    public function test_superadmin_can_view_region_admin_pages(): void
    {
        $superadmin = User::where('email', 'admin@example.com')->first();

        $this->actingAs($superadmin)
            ->get('/admin/admin-wilayah')
            ->assertOk()
            ->assertSee('Admin Semakuteng');
    }
}