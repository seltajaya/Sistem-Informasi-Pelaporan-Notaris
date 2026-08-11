<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $regions = Region::insertOrIgnore([
            ['name' => 'MPD 1'],
            ['name' => 'MPD 2'],
            ['name' => 'Simakuteng'],
            ['name' => 'MPD Lainnya'],
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'region_id' => null,
        ]);

        User::factory()->create([
            'name' => 'Notaris MPD1',
            'email' => 'notaris1@example.com',
            'password' => bcrypt('notaris123'),
            'role' => 'notaris',
            'region_id' => Region::where('name', 'MPD 1')->first()->id,
        ]);

        User::factory()->create([
            'name' => 'Notaris MPD2',
            'email' => 'notaris2@example.com',
            'password' => bcrypt('notaris123'),
            'role' => 'notaris',
            'region_id' => Region::where('name', 'MPD 2')->first()->id,
        ]);
    }
}
