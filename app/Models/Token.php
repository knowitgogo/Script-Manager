<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'token',
        'disabled',
    ];

    protected $casts = [
        'disabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function createForUser(User $user, array $data): self
    {
        return $user->tokens()->create([
            'name' => $data['name'],
            'token' => $data['token'],
        ]);
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    public function toggleDisabled(): void
    {
        $this->disabled = ! $this->disabled;
        $this->save();
    }

    public static function paginateForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->tokens()->recent()->paginate($perPage);
    }

    public static function paginateForAdmin(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return static::query()
            ->with('user')
            ->search($search)
            ->recent()
            ->paginate($perPage);
    }

    public static function totalCount(): int
    {
        return static::query()->count('*');
    }

    public static function deleteRecord(self $token): void
    {
        static::query()->whereKey($token->getKey())->delete();
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('token', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }
}