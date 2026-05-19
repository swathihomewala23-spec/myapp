<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.dashboard');
        }
        return view('vendor-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        // Check if input is email or username
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $attemptAuth = Auth::guard('vendor')->attempt([
            $fieldType => $credentials['username'],
            'password' => $credentials['password'],
        ], $remember);

        if ($attemptAuth) {
            $request->session()->regenerate();
            return redirect()->route('vendor.dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
}
