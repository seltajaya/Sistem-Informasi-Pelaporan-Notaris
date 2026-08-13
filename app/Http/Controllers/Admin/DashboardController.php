<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ScopesRegion;
use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

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