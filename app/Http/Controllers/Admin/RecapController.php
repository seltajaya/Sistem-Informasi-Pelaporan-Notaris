<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ScopesRegion;
use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecapController extends Controller
{
    use ScopesRegion;

    private const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function annual(Request $request): View
    {
        $regionId = $this->regionScope($request);

        $years = Report::query()
            ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
            ->selectRaw('report_year, COUNT(*) as total_laporan, SUM(jumlah_akta) as total_akta,
                SUM(jumlah_disahkan) as total_disahkan, SUM(jumlah_dibukukan) as total_dibukukan,
                SUM(jumlah_wasiat) as total_wasiat, SUM(jumlah_protes) as total_protes')
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

        $months = Report::query()
            ->where('report_year', $year)
            ->when($regionId, fn ($q) => $q->where('region_id', $regionId))
            ->selectRaw('report_month, COUNT(*) as total_laporan, SUM(jumlah_akta) as total_akta,
                SUM(jumlah_disahkan) as total_disahkan, SUM(jumlah_dibukukan) as total_dibukukan,
                SUM(jumlah_wasiat) as total_wasiat, SUM(jumlah_protes) as total_protes')
            ->groupBy('report_month')
            ->orderBy('report_month')
            ->get();

        return view('admin.recap-monthly', [
            'year' => $year,
            'months' => $months,
            'monthsNames' => self::MONTHS,
            'regions' => Region::orderBy('name')->get(),
            'canSelectRegion' => $this->canSelectRegion($request),
        ]);
    }

    public function tracking(Request $request): View
    {
        $regionId = $this->regionScope($request);
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

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
            'regionId' => $regionId,
            'missing' => $missing,
            'monthsNames' => self::MONTHS,
            'canSelectRegion' => $this->canSelectRegion($request),
        ]);
    }
}