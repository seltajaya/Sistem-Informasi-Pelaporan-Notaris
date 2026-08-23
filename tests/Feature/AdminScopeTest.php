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

    public function test_admin_wilayah_only_has_notaris_and_kepatuhan(): void
    {
        $admin = User::where('email', 'adm.semakuteng@example.com')->first();
        $this->actingAs($admin);

        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('admin.reports.index'))->assertStatus(403);
        $this->get(route('admin.recap.annual'))->assertStatus(403);
        $this->get(route('admin.region-admins.index'))->assertStatus(403);

        $this->get(route('admin.notaris.index'))->assertOk();
        $this->get(route('admin.recap.tracking'))->assertOk();
    }

    public function test_superadmin_can_access_all_admin_routes(): void
    {
        $super = User::where('email', 'admin@example.com')->first();
        $this->actingAs($super);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.reports.index'))->assertOk();
        $this->get(route('admin.recap.annual'))->assertOk();
        $this->get(route('admin.region-admins.index'))->assertOk();
        $this->get(route('admin.notaris.index'))->assertOk();
        $this->get(route('admin.recap.tracking'))->assertOk();
    }
}
