<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRegionMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_and_regions_are_renamed_after_seed(): void
    {
        $this->seed();

        $names = Region::orderBy('name')->pluck('name')->all();
        $this->assertSame(['KOTA BENGKULU', 'RELEPARMU', 'SEMAKUTENG'], $names);
        $this->assertSame('semakuteng', Region::where('name', 'SEMAKUTENG')->first()->slug);
        $this->assertTrue(User::where('email', 'admin@example.com')->first()->isSuperAdmin());
        $this->assertTrue(User::where('email', 'adm.kotabengkulu@example.com')->first()->isAdminWilayah());
        $this->assertTrue(User::where('email', 'notaris1@example.com')->first()->isNotaris());
    }
}
