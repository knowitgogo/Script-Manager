<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials, bool $remember = false): ?string
    {
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            return 'admin.dashboard';
        }

        if (Auth::guard('manager')->attempt($credentials, $remember)) {
            return 'manager.dashboard';
        }

        return null;
    }
}
?>