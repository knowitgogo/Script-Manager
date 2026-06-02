<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class ManagerAuthController extends BaseController
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('manager')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('manager.dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('manager')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('manager.login');
    }

    public function dashboard()
    {
        $totalUsers = User::query()->count('*');
        $disabledUsers = User::query()->where('disabled', true)->count('*');

        return view('manager.dashboard', compact('totalUsers', 'disabledUsers'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($search = $request->query('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manager.users.index', compact('users'));
    }

    public function disable(User $user)
    {
        $user->disabled = true;
        $user->save();

        return back()->with('success', 'User disabled successfully.');
    }

    public function enable(User $user)
    {
        $user->disabled = false;
        $user->save();

        return back()->with('success', 'User enabled successfully.');
    }
}
