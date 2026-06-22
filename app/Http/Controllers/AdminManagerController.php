<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Services\ManagerService;

class AdminManagerController extends BaseController
{
    public function index(Request $request, ManagerService $managerService)
    {
        $managers = $managerService->paginateSearchResults($request->query('search'));

        if ($managers->lastPage() > 0 && $managers->currentPage() > $managers->lastPage()) {
            return redirect()->route('admin.managers.index', array_merge(
                $request->query(),
                ['page' => $managers->lastPage()]
            ))->with('error', __('messages.requested_page_not_found'));
        }

        return view('admin.managers.index', compact('managers'));
    }

    public function create()
    {
        return view('admin.create-manager');
    }

    public function store(Request $request, ManagerService $managerService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $managerService->createAccount($validated);

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'Manager created successfully.');
    }

    public function edit(\App\Models\Manager $manager)
    {
        return view('admin.managers.edit', compact('manager'));
    }

    public function update(Request $request, \App\Models\Manager $manager, ManagerService $managerService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers,email,' . $manager->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $managerService->updateProfile($manager, $validated);

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'Manager updated successfully.');
    }

    public function destroy(\App\Models\Manager $manager, ManagerService $managerService)
    {
        $managerService->delete($manager);

        return redirect()
            ->route('admin.managers.index')
            ->with('success', 'Manager deleted successfully.');
    }
    public function deleted()
    {
        $managers = app(ManagerService::class)->deleted(10);

        if ($managers->lastPage() > 0 && $managers->currentPage() > $managers->lastPage()) {
            return redirect()->route('admin.managers.deleted', array_merge(
                request()->query(),
                ['page' => $managers->lastPage()]
            ))->with('error', __('messages.requested_page_not_found'));
        }

        return view('admin.managers.deleted', compact('managers'));
    }

    public function restore($id)
    {
        app(ManagerService::class)->restore($id);

        return back()->with('success', 'Manager restored successfully.');
    }
}
