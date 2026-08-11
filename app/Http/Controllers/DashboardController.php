<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the notaris dashboard with report history.
     */
    public function index(Request $request): View
    {
        $reports = $request->user()->reports()
            ->latest('report_year')
            ->latest('report_month')
            ->paginate(10);

        return view('dashboard', ['reports' => $reports]);
    }
}