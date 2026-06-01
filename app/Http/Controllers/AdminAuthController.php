<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAuthController extends BaseController
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function status()
    {
        $totalAdmins = Admin::query()->count();
        $totalManagers = Manager::query()->count();
        $totalUsers = User::query()->count();

        return view('admin.status', compact('totalAdmins', 'totalManagers', 'totalUsers'));
    }

    public function requests()
    {
        return view('admin.requests');
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function showTokenGenerateForm()
    {
        return view('admin.token');
    }

    public function generateToken(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Str::random(64);

        return back()->with('token', $token);
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')->with('success', 'Admin account created successfully.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
