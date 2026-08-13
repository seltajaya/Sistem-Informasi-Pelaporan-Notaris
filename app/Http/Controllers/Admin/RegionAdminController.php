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