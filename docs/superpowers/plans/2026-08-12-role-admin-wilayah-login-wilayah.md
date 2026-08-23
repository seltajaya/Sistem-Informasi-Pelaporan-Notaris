# Role Superadmin, Admin Wilayah & Login Berbasis Wilayah — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengubah `admin` menjadi superadmin, menambah role admin wilayah yang mendaftarkan notaris, dan membuat login berbasis wilayah (3 wilayah) dengan landing berupa tombol wilayah.

**Architecture:** Satu app Laravel, role berbasis kolom `users.role` (`superadmin | admin_wilayah | notaris`) + scope data via `region_id`. Login `GET /login/{slug?}` memvalidasi kesesuaian wilayah; middleware `admin` (superadmin+admin_wilayah) dan `superadmin` memilah akses. Tidak ada dependency baru.

**Tech Stack:** Laravel 12, Blade, Tailwind, MySQL/MariaDB, PHPUnit (Pest-free, PHPUnit bawaan).

**Spec:** `docs/superpowers/specs/2026-08-12-role-admin-wilayah-login-wilayah-design.md`

## Global Constraints

- Role values: `superadmin`, `admin_wilayah`, `notaris` (string, no enum constraint).
- Region values (nama & slug): `semakuteng`/`semakuteng`, `RELEPARMU`/`releparmu`, `KOTA BENGKULU`/`kota-bengkulu`. Region lama `MPD Lainnya` dihapus.
- Login per wilayah: akun `notaris`/`admin_wilayah` harus punya region yang cocok dengan slug yang dipilih; `superadmin` bebas.
- Admin wilayah hanya melihat data region sendiri; superadmin bisa memfilter semua.
- Tidak ada halaman daftar publik (route `register` dihapus).
- Nama halaman admin (route prefix `admin.`) dipertahankan.
- Semua perubahan harus hijau: `php artisan test`.

---

### Task 1: Migrasi DB — slug region + role superadmin

**Files:**
- Create: `database/migrations/2026_08_12_000000_expand_roles_and_regions.php`
- Modify: `app/Models/Region.php`, `app/Models/User.php`, `database/seeders/DatabaseSeeder.php`

**Interfaces:**
- Produces: `Region` punya `slug` (fillable, unique). `User` punya `isSuperAdmin(): bool`, `isAdminWilayah(): bool`, `isAdmin(): bool` (superadmin ATAU admin_wilayah), `isNotaris(): bool`.

- [ ] **Step 1: Tulis test gagal**

Buat `tests/Feature/RoleRegionMigrationTest.php`:

```php
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
        $this->assertSame(['KOTA BENGKULU', 'RELEPARMU', 'semakuteng'], $names);
        $this->assertSame('semakuteng', Region::where('name', 'semakuteng')->first()->slug);
        $this->assertTrue(User::where('email', 'admin@example.com')->first()->isSuperAdmin());
        $this->assertTrue(User::where('email', 'adm.kotabengkulu@example.com')->first()->isAdminWilayah());
        $this->assertTrue(User::where('email', 'notaris1@example.com')->first()->isNotaris());
    }
}
```

- [ ] **Step 2: Run test — pastikan gagal**

Run: `php artisan test --filter=RoleRegionMigrationTest`
Expected: FAIL (region belum rename, method role belum ada).

- [ ] **Step 3: Tulis migrasi**

Create `database/migrations/2026_08_12_000000_expand_roles_and_regions.php`:

```php
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
        DB::table('regions')->where('name', 'semakuteng')->update(['name' => 'semakuteng', 'slug' => 'semakuteng']);
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
```

- [ ] **Step 4: Update model Region**

`app/Models/Region.php`:

```php
protected $fillable = ['name', 'slug'];
```

- [ ] **Step 5: Update model User — helper role**

`app/Models/User.php` — ganti `isAdmin()` dan tambah helper:

```php
public function isSuperAdmin(): bool
{
    return $this->role === 'superadmin';
}

public function isAdminWilayah(): bool
{
    return $this->role === 'admin_wilayah';
}

public function isAdmin(): bool
{
    return $this->isSuperAdmin() || $this->isAdminWilayah();
}

public function isNotaris(): bool
{
    return $this->role === 'notaris';
}
```

- [ ] **Step 6: Update DatabaseSeeder**

`database/seeders/DatabaseSeeder.php` — ganti blok run() menjadi:

```php
public function run(): void
{
    $regions = [
        'KOTA BENGKULU' => 'kota-bengkulu',
        'RELEPARMU' => 'releparmu',
        'semakuteng' => 'semakuteng',
    ];

    foreach ($regions as $name => $slug) {
        Region::updateOrCreate(['slug' => $slug], ['name' => $name, 'slug' => $slug]);
    }
    Region::whereNotIn('slug', array_values($regions))->delete();

    User::factory()->updateOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Superadmin', 'password' => bcrypt('admin123'), 'role' => 'superadmin', 'region_id' => null]
    );

    $regionBySlug = fn ($slug) => Region::where('slug', $slug)->first()->id;

    foreach ([
        ['name' => 'Admin semakuteng', 'email' => 'adm.semakuteng@example.com', 'region' => 'semakuteng'],
        ['name' => 'Admin Releparmu', 'email' => 'adm.releparmu@example.com', 'region' => 'releparmu'],
        ['name' => 'Admin Kota Bengkulu', 'email' => 'adm.kotabengkulu@example.com', 'region' => 'kota-bengkulu'],
    ] as $admin) {
        User::factory()->updateOrCreate(
            ['email' => $admin['email']],
            ['name' => $admin['name'], 'password' => bcrypt('admin123'), 'role' => 'admin_wilayah', 'region_id' => $regionBySlug($admin['region'])]
        );
    }

    User::factory()->updateOrCreate(
        ['email' => 'notaris1@example.com'],
        ['name' => 'Notaris semakuteng', 'password' => bcrypt('notaris123'), 'role' => 'notaris', 'region_id' => $regionBySlug('semakuteng')]
    );

    User::factory()->updateOrCreate(
        ['email' => 'notaris2@example.com'],
        ['name' => 'Notaris Kota Bengkulu', 'password' => bcrypt('notaris123'), 'role' => 'notaris', 'region_id' => $regionBySlug('kota-bengkulu')]
    );
}
```

Tambah `use App\Models\Region;` di atas file (sudah ada). Hapus referensi `MPD 1`/`MPD 2` lama.

- [ ] **Step 7: Run test — pastikan hijau**

Run: `php artisan test --filter=RoleRegionMigrationTest`
Expected: PASS.

- [ ] **Step 8: Commit**

```bash
git add database/migrations/2026_08_12_000000_expand_roles_and_regions.php app/Models/Region.php app/Models/User.php database/seeders/DatabaseSeeder.php tests/Feature/RoleRegionMigrationTest.php
git commit -m "feat: migrasi role superadmin + 3 region ber-slug"
```

---

### Task 2: Login berbasis wilayah + superadmin login

**Files:**
- Modify: `routes/web.php`, `app/Http/Controllers/Auth/AuthenticatedSessionController.php`, `resources/views/auth/login.blade.php`, `resources/views/welcome.blade.php`
- Test: `tests/Feature/RegionLoginTest.php`

**Interfaces:**
- Consumes: `User::isSuperAdmin()`, `Region::slug` dari Task 1.
- Produces: route `login` menerima `{slug?}`; controller `create(Request $request, ?string $slug = null)`; POST login memvalidasi wilayah dan mengembalikan error `email` berpesan "Akun tidak terdaftar di wilayah ini." jika tidak cocok.

- [ ] **Step 1: Tulis test gagal**

Create `tests/Feature/RegionLoginTest.php`:

```php
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
        $notaris = User::where('email', 'notaris1@example.com')->first(); // semakuteng
        $this->assertSame('semakuteng', Region::find($notaris->region_id)->slug);

        $response = $this->post('/login/kota-bengkulu', [
            'email' => 'notaris1@example.com',
            'password' => 'notaris123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_notaris_login_from_own_region_succeeds(): void
    {
        $this->post('/login/semakuteng', [
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
        $this->get('/login/semakuteng')->assertSee('semakuteng');
    }
}
```

- [ ] **Step 2: Run test — pastikan gagal**

Run: `php artisan test --filter=RegionLoginTest`
Expected: FAIL (route `/login/{slug}` belum ada / validasi wilayah belum ada).

- [ ] **Step 3: Update route login**

`routes/web.php` — ganti route login di bagian akhir (sebelum `require __DIR__.'/auth.php';`) dengan override. Tambahkan blok ini SETELAH `require __DIR__.'/auth.php';` agar menimpa route Breeze:

```php
Route::get('/login/{slug?}', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('admin.login');
```

Tambah import `use App\Http\Controllers\Auth\AuthenticatedSessionController;` di `routes/web.php`.

- [ ] **Step 4: Update AuthenticatedSessionController**

`app/Http/Controllers/Auth/AuthenticatedSessionController.php`:

```php
use Illuminate\Validation\ValidationException;

public function create(Request $request, ?string $slug = null): View
{
    $region = null;
    if ($slug) {
        $region = \App\Models\Region::where('slug', $slug)->firstOrFail();
    }

    return view('auth.login', ['region' => $region]);
}

public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = $request->user();
    $region = $request->input('region_slug')
        ? \App\Models\Region::where('slug', $request->input('region_slug'))->first()
        : null;

    if ($region && ! $user->isSuperAdmin() && $user->region?->slug !== $region->slug) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => 'Akun tidak terdaftar di wilayah ini.',
        ]);
    }

    $request->session()->regenerate();

    return redirect()->intended(
        $user->isAdmin() ? route('admin.dashboard', absolute: false) : route('dashboard', absolute: false)
    );
}
```

- [ ] **Step 5: Update view login (region-aware)**

`resources/views/auth/login.blade.php` — di dalam `<x-auth-card ...>` ubah bagian heading dan form action + hidden input:

```blade
<x-auth-card :title="'Masuk ke Akun' . ($region ? ' — ' . $region->name : '')"
    :subtitle="'Sistem Informasi Pelaporan Notaris — Kanwil Kemenkumham Bengkulu'">

    @if ($region)
        <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-emas-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-kumham-800">
            Wilayah: {{ $region->name }}
        </div>
    @endif

    <form method="POST" action="{{ route('login', ['slug' => $region?->slug]) }}">
        @csrf
        @if ($region)
            <input type="hidden" name="region_slug" value="{{ $region->slug }}">
        @endif
        {{-- sisanya sama: email, password, remember, lupa password --}}
```

(Pertahankan field email/password/remember dan tombol submit yang sudah ada.)

- [ ] **Step 6: Update landing (welcome) — tombol wilayah**

`resources/views/welcome.blade.php` — ganti blok CTA hero (`@auth ... @else ... @endauth`) menjadi tombol 3 wilayah:

```blade
<div class="mt-8 grid w-full max-w-3xl grid-cols-1 gap-4 sm:grid-cols-3">
    @foreach ([
        ['name' => 'semakuteng', 'slug' => 'semakuteng', 'desc' => 'Seluma, Bengkulu Selatan, Manna, Kaur'],
        ['name' => 'RELEPARMU', 'slug' => 'releparmu', 'desc' => 'Rejang Lebong, Lebong, Kepahiang'],
        ['name' => 'KOTA BENGKULU', 'slug' => 'kota-bengkulu', 'desc' => 'Kota Bengkulu & sekitarnya'],
    ] as $wilayah)
        <a href="{{ route('login', ['slug' => $wilayah['slug']]) }}"
            class="group rounded-xl border border-white/20 bg-white/10 p-5 text-left backdrop-blur-sm transition duration-200 hover:bg-white/20 active:scale-[0.98]">
            <p class="text-lg font-extrabold uppercase tracking-wide text-white">{{ $wilayah['name'] }}</p>
            <p class="mt-1 text-sm text-white/70">{{ $wilayah['desc'] }}</p>
            <p class="mt-3 text-sm font-bold text-emas-300 group-hover:text-emas-200">Masuk &rarr;</p>
        </a>
    @endforeach
</div>
```

Hapus blok `@auth ... @else ... @endauth` di hero (ganti dengan tombol wilayah di atas). Bagian stat strip tetap.

- [ ] **Step 7: Run test — pastikan hijau**

Run: `php artisan test --filter=RegionLoginTest`
Expected: PASS.

- [ ] **Step 8: Commit**

```bash
git add routes/web.php app/Http/Controllers/Auth/AuthenticatedSessionController.php resources/views/auth/login.blade.php resources/views/welcome.blade.php tests/Feature/RegionLoginTest.php
git commit -m "feat: login berbasis wilayah + superadmin login via URL"
```

---

### Task 3: Middleware superadmin & scope admin wilayah

**Files:**
- Create: `app/Http/Middleware/EnsureSuperAdmin.php`
- Modify: `bootstrap/app.php`, `app/Http/Middleware/EnsureAdmin.php`, `app/Http/Controllers/Admin/DashboardController.php`, `app/Http/Controllers/Admin/ReportController.php`, `app/Http/Controllers/Admin/RecapController.php`, `resources/views/layouts/navigation.blade.php`, `resources/views/admin/dashboard.blade.php`, `resources/views/admin/reports.blade.php`, `resources/views/admin/recap-annual.blade.php`, `resources/views/admin/recap-monthly.blade.php`, `resources/views/admin/tracking.blade.php`
- Test: `tests/Feature/AdminScopeTest.php`

**Interfaces:**
- Consumes: `User::isSuperAdmin()`, `User::isAdminWilayah()` dari Task 1.
- Produces: middleware alias `superadmin`; controller admin memakai helper `$this->regionScope($request): ?int` yang mengembalikan `region_id` milik admin wilayah (selalu discope) atau `request('region_id')` untuk superadmin.

- [ ] **Step 1: Tulis test gagal**

Create `tests/Feature/AdminScopeTest.php`:

```php
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

    public function test_admin_wilayah_only_sees_own_region_reports(): void
    {
        $regionA = Region::where('slug', 'semakuteng')->first();
        $regionB = Region::where('slug', 'kota-bengkulu')->first();
        $notarisB = User::where('email', 'notaris2@example.com')->first();

        Report::create([
            'user_id' => $notarisB->id,
            'region_id' => $regionB->id,
            'report_month' => 8,
            'report_year' => 2026,
            'file_path' => 'reports/x.pdf',
        ]);

        $admin = User::where('email', 'adm.semakuteng@example.com')->first();

        $this->actingAs($admin)
            ->get('/admin/laporan')
            ->assertDontSee('Notaris Kota Bengkulu');
    }

    public function test_admin_wilayah_cannot_access_superadmin_pages(): void
    {
        $admin = User::where('email', 'adm.semakuteng@example.com')->first();

        $this->actingAs($admin)->get('/admin/admin-wilayah')->assertForbidden();
    }

    public function test_superadmin_sees_all_region_reports(): void
    {
        $superadmin = User::where('email', 'admin@example.com')->first();

        $this->actingAs($superadmin)
            ->get('/admin/admin-wilayah')
            ->assertOk()
            ->assertSee('Admin semakuteng');
    }
}
```

- [ ] **Step 2: Run test — pastikan gagal**

Run: `php artisan test --filter=AdminScopeTest`
Expected: FAIL (route `/admin/admin-wilayah` belum ada, admin_wilayah belum discope).

- [ ] **Step 3: Middleware EnsureSuperAdmin**

Create `app/Http/Middleware/EnsureSuperAdmin.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isSuperAdmin(), 403);

        return $next($request);
    }
}
```

- [ ] **Step 4: Daftarkan alias**

`bootstrap/app.php` — dalam `$middleware->alias([...])`:

```php
$middleware->alias([
    'admin' => \App\Http\Middleware\EnsureAdmin::class,
    'superadmin' => \App\Http\Middleware\EnsureSuperAdmin::class,
]);
```

- [ ] **Step 5: Scope helper — tambahkan trait**

Create `app/Http/Controllers/Admin/ScopesRegion.php` (trait kecil):

```php
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

trait ScopesRegion
{
    private function regionScope(Request $request): ?int
    {
        if ($request->user()->isAdminWilayah()) {
            return $request->user()->region_id;
        }

        return $request->filled('region_id') ? (int) $request->input('region_id') : null;
    }

    private function canSelectRegion(Request $request): bool
    {
        return $request->user()->isSuperAdmin();
    }
}
```

- [ ] **Step 6: Update DashboardController**

`app/Http/Controllers/Admin/DashboardController.php` — tambah `use ScopesRegion;` dan terapkan scope:

```php
use App\Http\Controllers\Admin\ScopesRegion;

class DashboardController extends Controller
{
    use ScopesRegion;

    public function index(Request $request): View
    {
        $now = now();
        $year = $request->input('year', $now->year);
        $month = $request->input('month', $now->month);
        $regionId = $this->regionScope($request);

        $regions = Region::withCount('users')->get();

        $stats = Region::withCount(['reports' => function ($q) use ($year, $month, $regionId) {
            $q->where('report_year', $year)->where('report_month', $month)
              ->when($regionId, fn ($query) => $query->where('region_id', $regionId));
        }])->get();

        return view('admin.dashboard', [
            'regions' => $regions,
            'stats' => $stats,
            'year' => $year,
            'month' => $month,
            'regionId' => $regionId,
            'canSelectRegion' => $this->canSelectRegion($request),
            'recentReports' => Report::with(['user', 'region'])
                ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
                ->latest()
                ->limit(8)
                ->get(),
            'totalNotaris' => User::where('role', 'notaris')
                ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
                ->count(),
        ]);
    }
}
```

- [ ] **Step 7: Update ReportController**

`app/Http/Controllers/Admin/ReportController.php`:

```php
use App\Http\Controllers\Admin\ScopesRegion;

class ReportController extends Controller
{
    use ScopesRegion;

    public function index(Request $request): View
    {
        $regionId = $this->regionScope($request);

        $reports = Report::with(['user', 'region'])
            ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
            ->when($request->filled('month'), fn ($q) => $q->where('report_month', $request->month))
            ->when($request->filled('year'), fn ($q) => $q->where('report_year', $request->year))
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->q}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports', [
            'reports' => $reports,
            'regions' => Region::orderBy('name')->get(),
            'canSelectRegion' => $this->canSelectRegion($request),
        ]);
    }
}
```

- [ ] **Step 8: Update RecapController**

`app/Http/Controllers/Admin/RecapController.php` — tambah `use ScopesRegion;`, di ketiga method terapkan `$regionId = $this->regionScope($request);` dan ganti semua `$request->filled('region_id')` dengan `$regionId`:

```php
public function annual(Request $request): View
{
    $regionId = $this->regionScope($request);

    $years = Report::query()
        ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
        ->selectRaw('...') // isi sama seperti sekarang
        ->groupBy('report_year')
        ->orderByDesc('report_year')
        ->get();

    return view('admin.recap-annual', [
        'years' => $years,
        'regions' => Region::orderBy('name')->get(),
        'canSelectRegion' => $this->canSelectRegion($request),
    ]);
}

public function monthly(Request $request, int $year): View
{
    $regionId = $this->regionScope($request);
    // ganti where('region_id', $request->region_id) dengan ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
    // pass 'canSelectRegion' ke view
}

public function tracking(Request $request): View
{
    $regionId = $this->regionScope($request);

    $missing = collect();

    if ($regionId) {
        $region = Region::find($regionId);
        $all = $region->users()->where('role', 'notaris')->get();
        $submittedIds = Report::where('region_id', $regionId)
            ->where('report_month', $month)
            ->where('report_year', $year)
            ->pluck('user_id');
        $missing = $all->reject(fn ($u) => $submittedIds->contains($u->id));
    }

    return view('admin.tracking', [
        'regions' => Region::orderBy('name')->get(),
        'missing' => $missing,
        'monthsNames' => self::MONTHS,
        'canSelectRegion' => $this->canSelectRegion($request),
    ]);
}
```

- [ ] **Step 9: Sembunyikan dropdown wilayah untuk admin_wilayah (views)**

Di `resources/views/admin/dashboard.blade.php`, `admin/reports.blade.php`, `admin/recap-annual.blade.php`, `admin/recap-monthly.blade.php`, `admin/tracking.blade.php` — bungkus kontrol `<select id="region_id" ...>` + labelnya dengan:

```blade
@if ($canSelectRegion ?? true)
    {{-- dropdown wilayah yang sudah ada --}}
@endif
```

Untuk `admin/dashboard.blade.php`, `admin/reports.blade.php`, `admin/recap-*.blade.php`, `admin/tracking.blade.php`: pindahkan `<select name="region_id">` ke dalam blok `@if ($canSelectRegion ?? true)`.

- [ ] **Step 10: Update navigation (menu superadmin)**

`resources/views/layouts/navigation.blade.php` — di blok admin (desktop & responsive) tambahkan setelah link Laporan:

```blade
@if (Auth::user()->isSuperAdmin())
    <x-nav-link :href="route('admin.region-admins.index')" :active="request()->routeIs('admin.region-admins.*')">
        Admin Wilayah
    </x-nav-link>
@endif
```

- [ ] **Step 11: Run test — pastikan hijau**

Run: `php artisan test --filter=AdminScopeTest`
Expected: `test_admin_wilayah_cannot_access_superadmin_pages` masih FAIL sampai Task 4 menambah route `/admin/admin-wilayah`. Catatan: jalankan dengan `--filter` dan terima kegagalan yang hanya terkait route yang belum dibuat; yang scope report harus PASS. Jika ada kegagalan scope, perbaiki.

- [ ] **Step 12: Commit**

```bash
git add app/Http/Middleware/EnsureSuperAdmin.php bootstrap/app.php app/Http/Controllers/Admin resources/views/layouts/navigation.blade.php resources/views/admin tests/Feature/AdminScopeTest.php
git commit -m "feat: scope admin wilayah + middleware superadmin"
```

---

### Task 4: Manajemen Admin Wilayah & Pendaftaran Notaris

**Files:**
- Create: `app/Http/Controllers/Admin/RegionAdminController.php`, `app/Http/Controllers/Admin/NotarisController.php`, `resources/views/admin/region-admins.blade.php`, `resources/views/admin/notaris.blade.php`
- Modify: `routes/web.php`, `routes/auth.php` (hapus register), `resources/views/auth/register.blade.php` (hapus), `app/Http/Controllers/Auth/RegisteredUserController.php` (hapus), `tests/Feature/Auth/RegistrationTest.php` (hapus)
- Test: `tests/Feature/NotarisRegistrationTest.php`

**Interfaces:**
- Consumes: `User` helper role dari Task 1, middleware `superadmin` dari Task 3.
- Produces: route `admin.region-admins.index|store|destroy`, `admin.notaris.index|store`. Notaris dibuat dengan region otomatis: admin_wilayah → regionnya, superadmin → `region_id` dari input.

- [ ] **Step 1: Tulis test gagal**

Create `tests/Feature/NotarisRegistrationTest.php`:

```php
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
}
```

- [ ] **Step 2: Run test — pastikan gagal**

Run: `php artisan test --filter=NotarisRegistrationTest`
Expected: FAIL (route belum ada, `/register` masih ada).

- [ ] **Step 3: RegionAdminController**

Create `app/Http/Controllers/Admin/RegionAdminController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegionAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.region-admins', [
            'admins' => User::with('region')->where('role', 'admin_wilayah')->orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'region_id' => ['required', 'exists:regions,id'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin_wilayah',
            'region_id' => $data['region_id'],
        ]);

        return redirect()->route('admin.region-admins.index')->with('status', 'Admin wilayah berhasil ditambahkan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin_wilayah', 403);
        $user->delete();

        return redirect()->route('admin.region-admins.index')->with('status', 'Admin wilayah dihapus.');
    }
}
```

- [ ] **Step 4: NotarisController**

Create `app/Http/Controllers/Admin/NotarisController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class NotarisController extends Controller
{
    use ScopesRegion;

    public function index(Request $request): View
    {
        $regionId = $this->regionScope($request);

        $notaris = User::where('role', 'notaris')
            ->with('region')
            ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
            ->orderBy('name')
            ->get();

        return view('admin.notaris', [
            'notaris' => $notaris,
            'canSelectRegion' => $this->canSelectRegion($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'notaris',
            'region_id' => $request->user()->isAdminWilayah()
                ? $request->user()->region_id
                : (int) $request->input('region_id'),
        ]);

        return redirect()->route('admin.notaris.index')->with('status', 'Notaris berhasil didaftarkan.');
    }
}
```

- [ ] **Step 5: Route baru + hapus register**

`routes/web.php` — dalam grup `admin` (`middleware(['auth', 'verified', 'admin'])`) tambahkan:

```php
Route::get('/admin-wilayah', [Admin\RegionAdminController::class, 'index'])->middleware('superadmin')->name('region-admins.index');
Route::post('/admin-wilayah', [Admin\RegionAdminController::class, 'store'])->middleware('superadmin')->name('region-admins.store');
Route::delete('/admin-wilayah/{user}', [Admin\RegionAdminController::class, 'destroy'])->middleware('superadmin')->name('region-admins.destroy');

Route::get('/notaris', [Admin\NotarisController::class, 'index'])->name('notaris.index');
Route::post('/notaris', [Admin\NotarisController::class, 'store'])->name('notaris.store');
```

Import: `use App\Http\Controllers\Admin\NotarisController;` dan `use App\Http\Controllers\Admin\RegionAdminController;`.

Hapus dari `routes/auth.php` (file di-rewrite, buang register):

```php
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');
```

Hapus file `app/Http/Controllers/Auth/RegisteredUserController.php` dan `resources/views/auth/register.blade.php`. Hapus `tests/Feature/Auth/RegistrationTest.php`.

- [ ] **Step 6: View admin/region-admins.blade.php**

Create `resources/views/admin/region-admins.blade.php` (pakai `x-app-layout`; tabel admin + form tambah; tombol hapus via `DELETE` form):

```blade
<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Superadmin</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Manajemen Admin Wilayah</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">
            <div class="card-panel overflow-hidden lg:col-span-2">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Daftar Admin Wilayah</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Email</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Wilayah</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($admins as $admin)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-kumham-900">{{ $admin->name }}</td>
                                    <td class="px-6 py-4">{{ $admin->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-kumham-50 px-2.5 py-0.5 text-xs font-semibold text-kumham-700">{{ $admin->region?->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.region-admins.destroy', $admin) }}" onsubmit="return confirm('Hapus admin wilayah ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-sm font-bold text-red-600 hover:text-red-500">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada admin wilayah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Tambah Admin Wilayah</h3>
                </div>
                <form method="POST" action="{{ route('admin.region-admins.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="region_id" :value="__('Wilayah')" />
                        <select id="region_id" name="region_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('region_id')" class="mt-2" />
                    </div>
                    <x-primary-button>Simpan</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
```

- [ ] **Step 7: View admin/notaris.blade.php**

Create `resources/views/admin/notaris.blade.php` (mirip region-admins; tabel notaris + form daftarkan; tambahkan dropdown wilayah hanya jika `$canSelectRegion`):

```blade
<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-kumham-700">Pendaftaran</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-kumham-950">Daftarkan Notaris</h2>
            <p class="mt-1 text-sm text-gray-500">Buat akun notaris peserta pelaporan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">
            <div class="card-panel overflow-hidden lg:col-span-2">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Daftar Notaris</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-kumham-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Email</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider text-kumham-800">Wilayah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($notaris as $n)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-kumham-900">{{ $n->name }}</td>
                                    <td class="px-6 py-4">{{ $n->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-kumham-50 px-2.5 py-0.5 text-xs font-semibold text-kumham-700">{{ $n->region?->name }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">Belum ada notaris terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-panel overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="font-bold text-kumham-900">Tambah Notaris</h3>
                </div>
                <form method="POST" action="{{ route('admin.notaris.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password" :value="__('Password Awal')" />
                        <x-text-input id="password" class="mt-1 block w-full" type="text" name="password" value="notaris123" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    @if ($canSelectRegion)
                        <div>
                            <x-input-label for="region_id" :value="__('Wilayah')" />
                            <select id="region_id" name="region_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('region_id')" class="mt-2" />
                        </div>
                    @endif
                    <x-primary-button>Daftarkan</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
```

Catatan: view `admin/notaris.blade.php` memakai variabel `$regions` hanya untuk superadmin. Ubah `NotarisController@index` agar selalu mengirim `$regions`:

```php
return view('admin.notaris', [
    'notaris' => $notaris,
    'regions' => \App\Models\Region::orderBy('name')->get(),
    'canSelectRegion' => $this->canSelectRegion($request),
]);
```

- [ ] **Step 8: Update route navigasi — tambah menu Notaris**

`resources/views/layouts/navigation.blade.php` — setelah link "Laporan" tambahkan (desktop & responsive):

```blade
<x-nav-link :href="route('admin.notaris.index')" :active="request()->routeIs('admin.notaris.*')">
    Notaris
</x-nav-link>
```

- [ ] **Step 9: Run test — pastikan hijau**

Run: `php artisan test --filter=NotarisRegistrationTest`
Expected: PASS semua (termasuk `test_public_register_page_is_gone`).

- [ ] **Step 10: Commit**

```bash
git add app/Http/Controllers/Admin/RegionAdminController.php app/Http/Controllers/Admin/NotarisController.php resources/views/admin/region-admins.blade.php resources/views/admin/notaris.blade.php routes/web.php routes/auth.php app/Http/Controllers/Auth/RegisteredUserController.php resources/views/auth/register.blade.php tests/Feature/Auth/RegistrationTest.php tests/Feature/NotarisRegistrationTest.php
git commit -m "feat: manajemen admin wilayah + pendaftaran notaris oleh admin"
```

---

### Task 5: Finalisasi — test lama, seed ulang, smoke test

**Files:**
- Modify: `tests/Feature/Auth/AuthenticationTest.php` (route `/login` sekarang `/login/{slug?}` — pastikan tetap valid), `tests/Feature/ReportFlowTest.php`, `README.md`
- Run: seluruh suite + smoke test nginx

- [ ] **Step 1: Perbaiki test lama yang terkait role**

`tests/Feature/ReportFlowTest.php` — ganti semua `'role' => 'admin'` menjadi `'role' => 'superadmin'`.

`tests/Feature/Auth/AuthenticationTest.php` — verifikasi test `test_users_can_authenticate_using_the_login_screen` masih POST ke `route('login')`; jika gagal karena route membutuhkan slug, gunakan `$this->post(route('login'))`.

- [ ] **Step 2: Jalankan seluruh suite**

Run: `php artisan test`
Expected: SEMUA PASS.

- [ ] **Step 3: Reset DB & seed ulang**

```bash
php artisan migrate:fresh --seed
```

- [ ] **Step 4: Smoke test via nginx**

Jalankan nginx+php-cgi jika belum (lihat README), lalu:

```bash
curl -s -o NUL -w "%{http_code}\n" http://127.0.0.1:8080/            # 200
curl -s -o NUL -w "%{http_code}\n" http://127.0.0.1:8080/login/semakuteng  # 200
curl -s -o NUL -w "%{http_code}\n" http://127.0.0.1:8080/admin/login # 200
curl -s -o NUL -w "%{http_code}\n" http://127.0.0.1:8080/register    # 404
```

Login manual via browser: `adm.semakuteng@example.com/admin123` dari `/login/semakuteng` → dashboard admin wilayah; `admin@example.com/admin123` dari `/admin/login` → dashboard superadmin.

- [ ] **Step 5: Update README**

`README.md` — update tabel akun seed (Superadmin + 3 Admin Wilayah + 2 Notaris), tambah section "Login Berbasis Wilayah", hapus instruksi "Daftar Notaris publik".

- [ ] **Step 6: Commit**

```bash
git add tests README.md
git commit -m "chore: perbaiki test role, update README, smoke test"
```

---

## Self-Review Checklist

- [ ] Spec 1 (superadmin): Task 1 (role rename), Task 3 (middleware superadmin + akses penuh), Task 4 (menu Admin Wilayah).
- [ ] Spec 2 (admin wilayah daftarkan notaris): Task 4.
- [ ] Spec 3 (halaman depan tombol wilayah): Task 2 Step 6.
- [ ] Spec 4 (login dikunci wilayah, superadmin via URL): Task 2 Steps 3-5.
- [ ] Scope admin wilayah (laporan/rekap/tracking): Task 3 Steps 6-9.
- [ ] Seed & test: Task 1 Step 6, Task 5.
- [ ] Tidak ada placeholder/TODO.
- [ ] Nama route konsisten (`admin.region-admins.*`, `admin.notaris.*`).
