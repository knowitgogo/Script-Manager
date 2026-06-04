<?php

namespace App\Models;

use Database\Factories\ManagerFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class Manager extends Authenticatable
{
    /** @use HasFactory<ManagerFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public static function createAccount(array $data): self
    {
        return static::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    public function updateProfile(array $data): void
    {
        $this->name = $data['name'];
        $this->email = $data['email'];

        if (! empty($data['password'])) {
            $this->password = $data['password'];
        }

        $this->save();
    }

    public function toggleDisabled(): void
    {
        $this->disabled = ! $this->disabled;
        $this->save();
    }

    public static function deleteRecord(self $manager): void
    {
        static::query()->whereKey($manager->getKey())->delete();
    }

    public static function paginateSearchResults(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return static::query()
            ->search($search)
            ->latest()
            ->paginate($perPage);
    }

    public static function deletedRecords(int $perPage = null)
    {
        $query = static::onlyTrashed()->latest('deleted_at');

        return $perPage === null ? $query->get() : $query->paginate($perPage);
    }

    public static function restoreTrashed(int $id): void
    {
        static::onlyTrashed()->findOrFail($id)->restore();
    }

    public static function totalCount(): int
    {
        return static::query()->count('*');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}