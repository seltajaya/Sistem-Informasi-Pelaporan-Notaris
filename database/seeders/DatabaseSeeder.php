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
        $regions = [
            'KOTA BENGKULU' => 'kota-bengkulu',
            'RELEPARMU' => 'releparmu',
            'SEMAKUTENG' => 'semakuteng',
        ];

        foreach ($regions as $name => $slug) {
            Region::updateOrCreate(['slug' => $slug], ['name' => $name, 'slug' => $slug]);
        }
        Region::whereNotIn('slug', array_values($regions))->delete();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Superadmin', 'password' => bcrypt('admin123'), 'role' => 'superadmin', 'region_id' => null]
        );

        $regionBySlug = fn ($slug) => Region::where('slug', $slug)->first()->id;

        foreach ([
            ['name' => 'Admin Semakuteng', 'email' => 'adm.semakuteng@example.com', 'region' => 'semakuteng'],
            ['name' => 'Admin Releparmu', 'email' => 'adm.releparmu@example.com', 'region' => 'releparmu'],
            ['name' => 'Admin Kota Bengkulu', 'email' => 'adm.kotabengkulu@example.com', 'region' => 'kota-bengkulu'],
        ] as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                ['name' => $admin['name'], 'password' => bcrypt('admin123'), 'role' => 'admin_wilayah', 'region_id' => $regionBySlug($admin['region'])]
            );
        }

        User::updateOrCreate(
            ['email' => 'notaris1@example.com'],
            ['name' => 'Notaris Semakuteng', 'password' => bcrypt('notaris123'), 'role' => 'notaris', 'region_id' => $regionBySlug('semakuteng')]
        );

        User::updateOrCreate(
            ['email' => 'notaris2@example.com'],
            ['name' => 'Notaris Kota Bengkulu', 'password' => bcrypt('notaris123'), 'role' => 'notaris', 'region_id' => $regionBySlug('kota-bengkulu')]
        );
    }
}
