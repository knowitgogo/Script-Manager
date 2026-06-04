<?php

namespace App\Models;

use App\Models\Token;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

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

    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
        $this->save();
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    public function tokensForListing(int $perPage = 10): LengthAwarePaginator
    {
        return $this->tokens()->recent()->paginate($perPage);
    }

    public static function paginateSearchResults(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return static::query()
            ->search($search)
            ->latest()
            ->paginate($perPage);
    }

    public static function totalCount(): int
    {
        return static::query()->count('*');
    }

    public static function disabledCount(): int
    {
        return static::query()->where('disabled', true)->count();
    }

    public static function deletedRecords(): Collection
    {
        return static::onlyTrashed()->get();
    }

    public static function deleteRecord(self $user): void
    {
        static::query()->whereKey($user->getKey())->delete();
    }

    public static function restoreTrashed(int $id): void
    {
        static::onlyTrashed()->findOrFail($id)->restore();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'disabled' => 'boolean',
        ];
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
}
