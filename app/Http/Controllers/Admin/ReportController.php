<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ScopesRegion;
use App\Models\Region;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function show(Report $report): View
    {
        return view('admin.report', ['report' => $report->load(['user', 'region'])]);
    }

    public function download(Report $report): StreamedResponse
    {
        return Storage::download($report->file_path);
    }
}