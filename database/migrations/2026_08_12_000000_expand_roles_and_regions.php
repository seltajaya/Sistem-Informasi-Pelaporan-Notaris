<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        DB::table('regions')->where('name', 'MPD 1')->update(['name' => 'KOTA BENGKULU', 'slug' => 'kota-bengkulu']);
        DB::table('regions')->where('name', 'MPD 2')->update(['name' => 'RELEPARMU', 'slug' => 'releparmu']);
        DB::table('regions')->where('name', 'Simakuteng')->update(['name' => 'SIMAKUTENG', 'slug' => 'simakuteng']);
        DB::table('regions')->where('name', 'MPD Lainnya')->delete();

        DB::table('users')->where('role', 'admin')->update(['role' => 'superadmin']);
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};