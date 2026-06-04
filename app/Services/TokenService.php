<?php

namespace App\Services;

use App\Models\Token;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TokenService
{
    public function paginateForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->tokens()
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function paginateForAdmin(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Token::query()
            ->with('user')
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('token', 'like', "%{$search}%");
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function totalCount(): int
    {
        return Token::query()->count('*');
    }

    public function createForUser(User $user, array $data): Token
    {
        return $user->tokens()->create([
            'name' => $data['name'],
            'token' => $data['token'],
        ]);
    }

    public function updateName(Token $token, string $name): void
    {
        $token->name = $name;
        $token->save();
    }

    public function delete(Token $token): void
    {
        Token::query()->whereKey($token->getKey())->delete();
    }

    public function toggleDisabled(Token $token): void
    {
        $token->disabled = ! $token->disabled;
        $token->save();
    }
}
