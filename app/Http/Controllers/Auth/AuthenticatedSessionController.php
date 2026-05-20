<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = $request->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard', absolute: false));
            case 'clerk':
                return redirect()->intended(route('clerk.dashboard', absolute: false));
            case 'judge':
                return redirect()->intended(route('judge.dashboard', absolute: false));
            case 'lawyer':
                return redirect()->intended(route('lawyer.dashboard', absolute: false));
            default:
                return redirect()->intended(route('public.dashboard', absolute: false));
        }
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
