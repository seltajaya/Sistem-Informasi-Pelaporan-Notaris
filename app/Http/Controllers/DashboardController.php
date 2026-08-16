<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the notaris dashboard with report history.
     */
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