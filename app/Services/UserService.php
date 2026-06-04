<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function paginateSearchResults(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->search($search)
            ->latest()
            ->paginate($perPage);
    }

    public function totalCount(): int
    {
        return User::query()->count('*');
    }

    public function disabledCount(): int
    {
        return User::query()->where('disabled', true)->count('*');
    }

    public function createAccount(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    public function updateProfile(User $user, array $data): void
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
    }

    public function delete(User $user): void
    {
        User::query()->whereKey($user->getKey())->delete();
    }

    public function restore(int $id): void
    {
        User::onlyTrashed()->findOrFail($id)->restore();
    }

    public function deleted(): Collection
    {
        return User::onlyTrashed()->get();
    }

    public function setDisabled(User $user, bool $disabled): void
    {
        $user->disabled = $disabled;
        $user->save();
    }
}
