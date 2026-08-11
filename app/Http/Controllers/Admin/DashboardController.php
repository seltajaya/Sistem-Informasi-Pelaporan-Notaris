<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $now = now();
        $year = $request->input('year', $now->year);
        $month = $request->input('month', $now->month);

        $regions = Region::withCount('users')->get();

        $stats = Region::withCount(['reports' => function ($q) use ($year, $month) {
            $q->where('report_year', $year)->where('report_month', $month);
        }])->get();

        return view('admin.dashboard', [
            'regions' => $regions,
            'stats' => $stats,
            'year' => $year,
            'month' => $month,
            'recentReports' => Report::with(['user', 'region'])
                ->latest()
                ->limit(8)
                ->get(),
            'totalNotaris' => User::where('role', 'notaris')->count(),
        ]);
    }
}