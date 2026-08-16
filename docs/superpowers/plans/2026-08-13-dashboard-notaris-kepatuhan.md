# Grafik & Daftar Kepatuhan di Dashboard Notaris — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Tampilkan grafik bar "Sudah/Belum Melapor" dan daftar nama notaris yang belum melapor di dashboard notaris, untuk bulan berjalan, lingkup wilayah notaris yang login.

**Architecture:** Perluas `DashboardController` untuk menghitung data kepatuhan (total/sudah/belum + daftar belum) lalu render di `dashboard.blade.php` sebagai bar chart CSS-only. Tanpa library JS baru.

**Tech Stack:** Laravel 12, Blade, Tailwind CSS, PHPUnit.

## Global Constraints

- Periode = bulan & tahun berjalan (`now()->month`, `now()->year`)
- Scope = wilayah user yang login (`$request->user()->region_id`)
- Jika `region_id` null → `totalNotaris=0`, `daftarBelum` kosong, blok grafik disembunyikan (`@if($totalNotaris > 0)`)
- Tidak menambah dependensi/library chart baru — murni CSS + Blade
- Ikuti pola test yang ada: `User::create`/`Report::create` langsung (bukan factory), `RefreshDatabase` + `seed()`

---
### Task 1: Data kepatuhan di DashboardController

**Files:**
- Modify: `app/Http/Controllers/DashboardController.php`
- Test: `tests/Feature/DashboardComplianceTest.php`

**Interfaces:**
- Produces: `DashboardController::index()` mengembalikan data tambahan pada view: `totalNotaris`, `sudahMelapor`, `belumMelapor`, `daftarBelum`, `regionName`, `month`, `year`

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/DashboardComplianceTest.php`:

```php
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
        $response->assertViewHas('daftarBelum', function ($list) use ($regionSimak) {
            $ids = $list->pluck('id')->all();
            return $ids === [User::where('email', 'notaris1@example.com')->value('id')];
        });
    }

    public function test_dashboard_hides_chart_when_no_region(): void
    {
        $superadmin = User::where('email', 'admin@example.com')->first();

        $this->actingAs($superadmin)
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan test --filter DashboardComplianceTest`
Expected: FAIL — "Undefined variable `$totalNotaris`" atau sejenisnya (variabel belum ada di view).

- [ ] **Step 3: Implementasi di controller**

Modify `app/Http/Controllers/DashboardController.php` menjadi:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $month = now()->month;
        $year = now()->year;

        $regionId = $request->user()->region_id;
        $regionName = $request->user()->region?->name;

        $totalNotaris = 0;
        $sudahMelapor = 0;
        $daftarBelum = collect();

        if ($regionId) {
            $totalNotaris = User::where('role', 'notaris')
                ->where('region_id', $regionId)->count();

            $sudahMelapor = Report::where('region_id', $regionId)
                ->where('report_month', $month)
                ->where('report_year', $year)
                ->distinct('user_id')->count('user_id');

            $submittedIds = Report::where('region_id', $regionId)
                ->where('report_month', $month)
                ->where('report_year', $year)
                ->pluck('user_id');

            $daftarBelum = User::where('role', 'notaris')
                ->where('region_id', $regionId)
                ->get()
                ->reject(fn (User $u) => $submittedIds->contains($u->id));
        }

        return view('dashboard', [
            'reports' => $request->user()->reports()
                ->latest('report_year')
                ->latest('report_month')
                ->paginate(10),
            'totalNotaris' => $totalNotaris,
            'sudahMelapor' => $sudahMelapor,
            'belumMelapor' => $totalNotaris - $sudahMelapor,
            'daftarBelum' => $daftarBelum,
            'regionName' => $regionName,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
```

- [ ] **Step 4: Jalankan test, pastikan lulus**

Run: `php artisan test --filter DashboardComplianceTest`
Expected: PASS (3 tests).

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/DashboardController.php tests/Feature/DashboardComplianceTest.php
git commit -m "feat: data kepatuhan bulan berjalan di dashboard notaris"
```

---
### Task 2: Grafik bar & daftar belum melapor di view

**Files:**
- Modify: `resources/views/dashboard.blade.php`

**Interfaces:**
- Consumes: `totalNotaris`, `sudahMelapor`, `belumMelapor`, `daftarBelum`, `regionName`, `month`, `year` (dari Task 1)

- [ ] **Step 1: Tambah card kepatuhan setelah "Panduan Pelaporan"**

Di `resources/views/dashboard.blade.php`, tepat setelah blok `card-panel` "Panduan Pelaporan" (berakhir sekitar baris 194) dan sebelum blok "Butuh Bantuan", sisipkan:

```blade
{{-- Kepatuhan Pelaporan --}}
@if ($totalNotaris > 0)
    <div class="card-panel mt-5 overflow-hidden">
        <div class="border-b border-gray-200 px-5 py-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-sm font-bold text-kumham-900">
                    Kepatuhan Pelaporan Wilayah {{ $regionName }}
                </h3>
                <span class="inline-flex rounded-full bg-kumham-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-kumham-700">
                    {{ \Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }} {{ $year }}
                </span>
            </div>
            <p class="mt-1 text-xs text-gray-500">
                Berapa banyak notaris yang sudah melapor bulan ini di wilayah Anda.
            </p>
        </div>

        <div class="space-y-4 px-5 py-5">
            {{-- Bar Sudah Melapor --}}
            <div>
                <div class="mb-1 flex items-center justify-between text-sm">
                    <span class="font-semibold text-green-700">Sudah Melapor</span>
                    <span class="font-bold text-kumham-900">
                        {{ $sudahMelapor }} dari {{ $totalNotaris }}
                        ({{ $totalNotaris ? round(($sudahMelapor / $totalNotaris) * 100) : 0 }}%)
                    </span>
                </div>
                <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-full rounded-full bg-green-500 transition-all"
                        style="width: {{ $totalNotaris ? round(($sudahMelapor / $totalNotaris) * 100) : 0 }}%"></div>
                </div>
            </div>

            {{-- Bar Belum Melapor --}}
            <div>
                <div class="mb-1 flex items-center justify-between text-sm">
                    <span class="font-semibold text-red-700">Belum Melapor</span>
                    <span class="font-bold text-kumham-900">
                        {{ $belumMelapor }} dari {{ $totalNotaris }}
                        ({{ $totalNotaris ? round(($belumMelapor / $totalNotaris) * 100) : 0 }}%)
                    </span>
                </div>
                <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-full rounded-full bg-red-500 transition-all"
                        style="width: {{ $totalNotaris ? round(($belumMelapor / $totalNotaris) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Daftar Belum Melapor --}}
        @if ($daftarBelum->isNotEmpty())
            <div class="border-t border-red-100 bg-red-50/50 px-5 py-4">
                <p class="mb-3 text-xs font-bold uppercase tracking-wider text-red-700">
                    Daftar notaris yang belum melapor:
                </p>
                <ul class="space-y-1.5">
                    @foreach ($daftarBelum as $notaris)
                        <li class="flex items-center gap-2 text-sm text-gray-700">
                            <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-[10px] font-bold text-red-700">!</span>
                            {{ $notaris->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="border-t border-green-100 bg-green-50/50 px-5 py-4">
                <p class="text-sm font-semibold text-green-700">
                    Semua notaris di wilayah ini sudah melapor.
                </p>
            </div>
        @endif
    </div>
@endif
```

- [ ] **Step 2: Jalankan semua test dashboard compliance**

Run: `php artisan test --filter DashboardComplianceTest`
Expected: PASS (3 tests).

- [ ] **Step 3: Verifikasi smoke visual**

Pastikan server berjalan, lalu akses `/dashboard` sebagai notaris:
- Login `notaris1@example.com` / `notaris123` via `/login/simakuteng`
- Harus terlihat card "Kepatuhan Pelaporan Wilayah SIMAKUTENG" dengan 2 bar (Sudah/Belum) dan daftar nama.

Jika server belum jalan:
```bash
powershell -ExecutionPolicy Bypass -File "D:\App\nginx\start-php-cgi.ps1"
powershell -ExecutionPolicy Bypass -File "D:\App\nginx\start-nginx.ps1"
```

- [ ] **Step 4: Commit**

```bash
git add resources/views/dashboard.blade.php
git commit -m "feat: grafik kepatuhan dan daftar belum melapor di dashboard notaris"
```

---
## Self-Review Checkpoints
- Spec item "bar chart sederhana Sudah vs Belum + jumlah orang" → Task 2 Step 1
- Spec item "daftar nama yang belum melapor (badge merah)" → Task 2 Step 1
- Spec item "periode bulan berjalan" → Task 1 Step 3
- Spec item "scope wilayah user" → Task 1 Step 3
- Spec item "jika total 0 → blok disembunyikan" → Task 2 Step 1 (`@if ($totalNotaris > 0)`)
- Spec item "test" → Task 1 Step 1 (3 test cases)
