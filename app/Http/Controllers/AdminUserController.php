<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Services\UserService;

class AdminUserController extends BaseController
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request, UserService $userService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $userService->createAccount($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User created successfully.']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (request()->wantsJson()) {
            return response()->json(compact('user'));
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user, UserService $userService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $userService->updateProfile($user, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User updated successfully.']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user, UserService $userService)
{
    $userService->delete($user);

    if (request()->wantsJson()) {
        return response()->json(['message' => 'User deleted successfully.']);
    }

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User deleted successfully.');
}
public function restore($id)
{
    app(UserService::class)->restore($id);

    if (request()->wantsJson()) {
        return response()->json(['message' => 'User restored successfully.']);
    }

    return back()->with('success', 'User restored successfully.');
}

public function deleted()
{
    $users = app(UserService::class)->deleted();

    if (request()->wantsJson()) {
        return response()->json($users);
    }

    return view('admin.users.deleted', compact('users'));
}

    public function toggleStatus(User $user)
    {
        app(UserService::class)->setDisabled($user, ! $user->disabled);

        $message = $user->disabled ? 'User disabled successfully.' : 'User enabled successfully.';

        if (request()->wantsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message);
    }
}
