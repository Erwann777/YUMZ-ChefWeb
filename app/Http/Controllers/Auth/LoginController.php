<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->isSuspended()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            ActivityLog::log('login', "{$user->name} logged in", $user->id, null, $request->ip());

            return redirect()->intended($this->redirectByRole($user));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::log('logout', "{$user->name} logged out", $user->id, null, $request->ip());
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get the redirect path based on user role.
     */
    protected function redirectByRole($user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'cooker' => route('cooker.dashboard'),
            default => route('dashboard'),
        };
    }
}
