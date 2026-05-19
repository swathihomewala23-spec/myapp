<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    private const DEFAULT_ADMIN_USERNAME = 'admin';
    private const DEFAULT_ADMIN_PASSWORD = 'admin123';

    public function showLoginForm()
    {
        return view('admin-login-light');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $admin = $this->ensureDefaultAdminAccount();
        $submittedUsername = trim((string) $request->input('username'));
        $submittedPassword = (string) $request->input('password');

        $isValidAdmin =
            hash_equals(self::DEFAULT_ADMIN_USERNAME, $submittedUsername) &&
            Hash::check($submittedPassword, $admin->password);

        if ($isValidAdmin) {
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'username' => __('auth.failed'),
        ]);
    }

    private function ensureDefaultAdminAccount(): Admin
    {
        $admin = Admin::firstOrNew(['username' => self::DEFAULT_ADMIN_USERNAME]);

        if (! $admin->exists) {
            $admin->name = 'Administrator';
            $admin->email = 'admin@localhost';
            $admin->password = Hash::make(self::DEFAULT_ADMIN_PASSWORD);
            $admin->save();
            return $admin;
        }

        if (! Hash::check(self::DEFAULT_ADMIN_PASSWORD, (string) $admin->password)) {
            $admin->password = Hash::make(self::DEFAULT_ADMIN_PASSWORD);
            $admin->save();
        }

        return $admin;
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
