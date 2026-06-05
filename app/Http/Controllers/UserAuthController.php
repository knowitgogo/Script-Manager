<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\UserService;


class UserAuthController extends BaseController
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request, UserService $userService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $userService->createAccount($validated);

        return redirect()->route('login')->with('success', 'Your account has been created successfully. Please log in.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function status()
    {
        return view('user.status');
    }

    public function requests()
    {
        return view('user.requests');
    }

    public function showTokenGenerateForm()
    {
        return view('user.token');
    }

    public function generateToken(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Str::random(64);

        return back()
            ->with('success', __('messages.token_generated_for', ['name' => $validated['name']]))
            ->with('token', $token)
            ->with('token_name', $validated['name']);
    }
    
public function deleted()
    {
        $users = app(UserService::class)->deleted();

        return view('admin.users.deleted', compact('users'));
    }
}

