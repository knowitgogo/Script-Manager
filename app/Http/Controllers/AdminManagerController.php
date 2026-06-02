<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class AdminManagerController extends BaseController
{
    public function index(Request $request)
    {
        $query = Manager::query();

        if ($search = $request->query('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $managers = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.managers.index', compact('managers'));
    }

    public function create()
    {
        return view('admin.create-manager');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Manager::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'Manager created successfully.');
    }

    public function edit(Manager $manager)
    {
        return view('admin.managers.edit', compact('manager'));
    }

    public function update(Request $request, Manager $manager)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers,email,' . $manager->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $manager->name = $request->name;
        $manager->email = $request->email;

        if ($request->filled('password')) {
            $manager->password = Hash::make($request->password);
        }

        $manager->save();

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'Manager updated successfully.');
    }

    public function destroy(Manager $manager)
    {
        Manager::whereKey($manager->id)->delete();

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'Manager deleted successfully.');
    }
}
