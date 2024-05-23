<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the regular user login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.admin_login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            
        ],
        [
            'email.required' => 'Полето за е-пошта е задолжително.',
            'email.email' => 'Е-поштата мора да биде валидна адреса.',
            'password.required' => 'Полето за лозинка е задолжително.',
        ]);
    
    
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
    
            return Auth::user()->role === 'admin' ? redirect()->intended(RouteServiceProvider::ADMIN_HOME) : redirect()->intended(RouteServiceProvider::HOME);
        }
    
        return back()->withErrors([
            'email' => 'Погрешна емајл адреса или лозинка.',
        ]);
    }
    

    /**
     * Handle an incoming admin authentication request.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            
        ],
        [
            'email.required' => 'Полето за е-пошта е задолжително.',
            'email.email' => 'Е-поштата мора да биде валидна адреса.',
            'password.required' => 'Полето за лозинка е задолжително.',
        ]);
    
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
    
            \Log::info('User role: ' . Auth::user()->role);
    
            if (!Auth::user()->isAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Овој логин панел е само за админи!']);
            }
    
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }
    
        return back()->withErrors([
            'email' => 'Погрешна емајл адреса или лозинка.',
        ]);
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
