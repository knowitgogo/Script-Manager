<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ManagerService
{
    public function paginateSearchResults(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Manager::query()
            ->search($search)
            ->latest()
            ->paginate($perPage);
    }

    public function totalCount(): int
    {
        return Manager::query()->count('*');
    }

    public function createAccount(array $data): Manager
    {
        return Manager::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    public function updateProfile(Manager $manager, array $data): void
    {
        $manager->name = $data['name'];
        $manager->email = $data['email'];

        if (! empty($data['password'])) {
            $manager->password = $data['password'];
        }

        $manager->save();
    }

    public function delete(Manager $manager): void
    {
        Manager::query()->whereKey($manager->getKey())->delete();
    }

    public function deleted(int $perPage = 10): LengthAwarePaginator|Collection
    {
        $query = Manager::onlyTrashed()->latest('deleted_at');

        return $perPage === 0 ? $query->get() : $query->paginate($perPage);
    }

    public function restore(int $id): void
    {
        Manager::onlyTrashed()->findOrFail($id)->restore();
    }
}
