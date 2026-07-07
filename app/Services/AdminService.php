<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Token;

class AdminService
{
    public function totalTokens(): int
    {
        return Token::query()->count('*');
    }

    public function totalAdmins(): int
    {
        return Admin::query()->count('*');
    }

    public function createAccount(array $data): Admin
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
