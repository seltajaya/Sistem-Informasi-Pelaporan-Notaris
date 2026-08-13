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