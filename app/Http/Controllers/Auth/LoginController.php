<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login page.
     */
    public function show(Request $request): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !$user->aktif) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return $this->authenticated($request, $user);
    }

    /**
     * Redirect user based on role.
     */
    protected function authenticated(Request $request, $user): RedirectResponse
    {
        return match ($user->role) {
            'admin', 'author' => redirect()->intended(route('admin.dashboard', absolute: false)),
            default => redirect()->intended(route('home', absolute: false)),
        };
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login.show')
                ->with('success', 'Anda telah logout.');
        } catch (\Exception $e) {
            // Jika session sudah invalid, tetap redirect ke login
            return redirect()->route('login.show')
                ->with('success', 'Anda telah logout.');
        }
    }
}