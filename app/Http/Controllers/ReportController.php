<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function create(): View
    {
        return view('reports.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'report_month' => ['required', 'integer', 'between:1,12'],
            'report_year' => ['required', 'integer', 'between:2000,2100'],
            'jumlah_akta' => ['required', 'integer', 'min:0'],
            'jumlah_disahkan' => ['required', 'integer', 'min:0'],
            'jumlah_dibukukan' => ['required', 'integer', 'min:0'],
            'jumlah_wasiat' => ['required', 'integer', 'min:0'],
            'jumlah_protes' => ['required', 'integer', 'min:0'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $user = $request->user();

        if ($user->reports()->where('report_month', $validated['report_month'])
            ->where('report_year', $validated['report_year'])->exists()) {
            return back()->withErrors([
                'report_month' => 'Laporan untuk bulan tersebut sudah ada.',
            ])->withInput();
        }

        $path = $request->file('file')->store('reports');

        $user->reports()->create([
            'report_month' => $validated['report_month'],
            'report_year' => $validated['report_year'],
            'jumlah_akta' => $validated['jumlah_akta'],
            'jumlah_disahkan' => $validated['jumlah_disahkan'],
            'jumlah_dibukukan' => $validated['jumlah_dibukukan'],
            'jumlah_wasiat' => $validated['jumlah_wasiat'],
            'jumlah_protes' => $validated['jumlah_protes'],
            'file_path' => $path,
        ]);

        return redirect()->route('dashboard')->with('status', 'Laporan berhasil dikirim.');
    }

    public function download(Request $request, Report $report): StreamedResponse
    {
        abort_unless($report->user_id === $request->user()->id, 403);

        return Storage::download($report->file_path, $this->fileName($report));
    }

    private function fileName(Report $report): string
    {
        return sprintf(
            'laporan-%s-%02d-%d.pdf',
            str_replace(' ', '-', $report->user->name),
            $report->report_month,
            $report->report_year
        );
    }
}