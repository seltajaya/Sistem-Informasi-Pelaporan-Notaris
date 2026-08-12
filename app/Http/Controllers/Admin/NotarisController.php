<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
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
            'regions' => Region::orderBy('name')->get(),
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