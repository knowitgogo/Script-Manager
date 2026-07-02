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

        if (request()->wantsJson()) {
            return response()->json([
                'totalTokens' => $totalTokens,
                'totalUsers' => $totalUsers
            ]);
        }

        return view('admin.dashboard', compact('totalTokens', 'totalUsers'));
    }

    public function status(AdminService $adminService, ManagerService $managerService, UserService $userService)
    {
        $totalAdmins = $adminService->totalAdmins();
        $totalManagers = $managerService->totalCount();
        $totalUsers = $userService->totalCount();

        if (request()->wantsJson()) {
            return response()->json(compact('totalAdmins', 'totalManagers', 'totalUsers'));
        }

        return view('admin.status', compact('totalAdmins', 'totalManagers', 'totalUsers'));
    }

    public function requests()
    {
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Requests loaded']);
        }
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

        if (request()->wantsJson()) {
            return response()->json($users);
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

        if ($request->wantsJson()) {
            return response()->json($tokens);
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

        $token = Str::random(16);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('messages.token_generated_for', ['name' => $validated['name']]),
                'token' => $token,
                'token_name' => $validated['name']
            ]);
        }

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
        \Log::info('Register attempt from frontend: ', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $adminService->createAccount($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Admin account created successfully.']);
        }

        return redirect()->route('admin.login')->with('success', 'Admin account created successfully.');
    }

    
public function login(Request $request, AuthService $authService)
{
    \Log::info('Login attempt from frontend: ', $request->all());

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
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Login successful', 'redirect' => route($route)]);
        }
        return redirect()->intended(route($route));
    }

    if ($request->wantsJson()) {
        return response()->json(['message' => 'Invalid email or password'], 422);
    }

    return back()->withErrors([
        'email' => 'Invalid email or password',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect()->route('admin.login');
    }
}
