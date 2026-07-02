<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

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

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Login successful', 'redirect' => route('manager.dashboard')]);
            }

            return redirect()->intended(route('manager.dashboard'));
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
        Auth::guard('manager')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect()->route('manager.login');
    }

    public function dashboard(Request $request, UserService $userService)
    {
        $totalUsers = $userService->totalCount();
        $disabledUsers = $userService->disabledCount();

        if ($request->wantsJson()) {
            return response()->json([
                'totalUsers' => $totalUsers,
                'disabledUsers' => $disabledUsers,
                'manager' => Auth::guard('manager')->user()
            ]);
        }

        return view('manager.dashboard', compact('totalUsers', 'disabledUsers'));
    }

    public function users(Request $request, UserService $userService)
    {
        $users = $userService->paginateSearchResults($request->query('search'));

        if ($users->lastPage() > 0 && $users->currentPage() > $users->lastPage()) {
            return redirect()->route('manager.users.index', array_merge(
                $request->query(),
                ['page' => $users->lastPage()]
            ))->with('error', __('messages.requested_page_not_found'));
        }

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('manager.users.index', compact('users'));
    }

    public function disable(Request $request, User $user, UserService $userService)
    {
        $userService->setDisabled($user, true);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User disabled successfully.']);
        }

        return back()->with('success', 'User disabled successfully.');
    }

    public function enable(Request $request, User $user, UserService $userService)
    {
        $userService->setDisabled($user, false);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User enabled successfully.']);
        }

        return back()->with('success', 'User enabled successfully.');
    }
}
