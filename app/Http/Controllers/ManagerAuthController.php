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

    public function dashboard(UserService $userService)
    {
        $totalUsers = $userService->totalCount();
        $disabledUsers = $userService->disabledCount();

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

        return view('manager.users.index', compact('users'));
    }

    public function disable(User $user, UserService $userService)
    {
        $userService->setDisabled($user, true);

        return back()->with('success', 'User disabled successfully.');
    }

    public function enable(User $user, UserService $userService)
    {
        $userService->setDisabled($user, false);

        return back()->with('success', 'User enabled successfully.');
    }
}
