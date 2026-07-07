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

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Login successful', 'redirect' => route('dashboard')]);
            }

            return redirect()->intended(route('dashboard'));
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'user' => $request->user()
            ]);
        }
        return view('user.dashboard');
    }

    public function status(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'user' => $request->user()
            ]);
        }
        return view('user.status');
    }

    public function requests(Request $request)
    {
        if ($request->expectsJson()) {
            $user = $request->user();
            
            $tokenUsages = \App\Models\TokenUsage::where('user_id', $user->id)
                ->with(['token:id,name', 'user:id,name'])
                ->latest()
                ->paginate(10);

            // Aggregate analytics: Top queries in the last 7 days
            $analytics = \App\Models\TokenUsage::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('query, count(*) as count')
                ->groupBy('query')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            return response()->json([
                'requests' => $tokenUsages->items(),
                'pagination' => [
                    'current_page' => $tokenUsages->currentPage(),
                    'last_page' => $tokenUsages->lastPage(),
                    'total' => $tokenUsages->total(),
                ],
                'analytics' => $analytics,
            ]);
        }
        return view('user.requests');
    }

    public function analytics(Request $request)
    {
        if ($request->expectsJson()) {
            $user = $request->user();

            // Requests per day for the last 30 days
            $daily = \App\Models\TokenUsage::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');

            // Build full 30-day range (fill gaps with 0)
            $result = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $result[] = [
                    'date'  => $date,
                    'count' => isset($daily[$date]) ? (int) $daily[$date]->count : 0,
                ];
            }

            return response()->json([
                'activity' => $result,
                'total'    => array_sum(array_column($result, 'count')),
            ]);
        }

        return response()->json(['error' => 'JSON expected'], 400);
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

        $token = Str::random(16);

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

