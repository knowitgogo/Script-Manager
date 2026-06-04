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

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
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

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user, UserService $userService)
{
    $userService->delete($user);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User deleted successfully.');
}
public function restore($id)
{
    app(UserService::class)->restore($id);

    return back()->with('success', 'User restored successfully.');
}

public function deleted()
{
    $users = app(UserService::class)->deleted();

    return view('admin.users.deleted', compact('users'));
}

    public function toggleStatus(User $user)
    {
        app(UserService::class)->setDisabled($user, ! $user->disabled);

        $message = $user->disabled ? 'User disabled successfully.' : 'User enabled successfully.';

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message);
    }
}
