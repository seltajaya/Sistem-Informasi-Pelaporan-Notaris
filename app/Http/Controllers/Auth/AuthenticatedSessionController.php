<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request, ?string $slug = null): View
    {
        $region = null;
        if ($slug) {
            $region = \App\Models\Region::where('slug', $slug)->firstOrFail();
        }

        return view('auth.login', ['region' => $region]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, ?string $slug = null): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        if ($slug && ! $user->isSuperAdmin()) {
            $region = \App\Models\Region::where('slug', $slug)->first();

            if (! $region || $user->region?->slug !== $region->slug) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Akun tidak terdaftar di wilayah ini.',
                ]);
            }
        }

        $request->session()->regenerate();

        return redirect()->intended(
            match ($user->role) {
                'superadmin' => route('admin.dashboard', absolute: false),
                'admin_wilayah' => route('admin.notaris.index', absolute: false),
                default => route('dashboard', absolute: false),
            }
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
