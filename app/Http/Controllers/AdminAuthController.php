<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\AuthService;
use App\Services\AdminService;
use App\Services\ManagerService;
use App\Services\TokenService;
use App\Services\UserService;

class AdminAuthController extends BaseController
{   
    public function dashboard(TokenService $tokenService, UserService $userService)
    {
        $totalTokens = $tokenService->totalCount();
        $totalUsers = $userService->totalCount();

        return view('admin.dashboard', compact('totalTokens', 'totalUsers'));
    }

    public function status(AdminService $adminService, ManagerService $managerService, UserService $userService)
    {
        $totalAdmins = $adminService->totalAdmins();
        $totalManagers = $managerService->totalCount();
        $totalUsers = $userService->totalCount();

        return view('admin.status', compact('totalAdmins', 'totalManagers', 'totalUsers'));
    }

    public function requests()
    {
        return view('admin.requests');
    }

    public function users(UserService $userService)
    {
        $users = $userService->paginateSearchResults(request('search'));

        if ($users->lastPage() > 0 && $users->currentPage() > $users->lastPage()) {
            return redirect()->route('admin.users.index', array_merge(
                request()->query(),
                ['page' => $users->lastPage()]
            ))->with('error', __('messages.requested_page_not_found'));
        }

        return view('admin.users.index', compact('users'));
    }

    public function tokens(Request $request, TokenService $tokenService)
    {
        $tokens = $tokenService->paginateForAdmin($request->query('search'));

        if ($tokens->lastPage() > 0 && $tokens->currentPage() > $tokens->lastPage()) {
            return redirect()->route('admin.tokens.index', array_merge(
                $request->query(),
                ['page' => $tokens->lastPage()]
            ))->with('error', __('messages.requested_page_not_found'));
        }

        return view('admin.tokens.index', compact('tokens'));
    }

    public function showTokenGenerateForm()
    {
        return view('admin.token');
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

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function register(Request $request, AdminService $adminService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $adminService->createAccount($validated);

        return  redirect()->route('admin.login')->with('success', 'Admin account created successfully.');
    }

    
public function login(Request $request, AuthService $authService)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $route = $authService->login(
        $request->only('email', 'password'),
        $request->boolean('remember')
    );

    if ($route) {
        $request->session()->regenerate();

        return redirect()->intended(route($route));
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
