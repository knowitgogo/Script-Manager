<?php

use Illuminate\Support\Facades\Route;
use App\Models\Admin;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminManagerController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ManagerAuthController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserTokenController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;

Route::get('/test-admin', function () {
    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'test@example.com',
        'password' => bcrypt('123456'),
    ]);

    return $admin;
});

Route::get('/register', [UserAuthController::class, 'showRegistrationForm'])
    ->name('register');

Route::post('/register', [UserAuthController::class, 'register'])
    ->name('register.post');

Route::get('/login', [UserAuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [UserAuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [UserAuthController::class, 'logout'])
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserAuthController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/status', [UserAuthController::class, 'status'])
        ->name('status');

    Route::get('/requests', [UserAuthController::class, 'requests'])
        ->name('requests');

    Route::get('/token/generate', [UserTokenController::class, 'create'])
        ->name('token.generate');

    Route::post('/token/generate', [UserTokenController::class, 'store'])
        ->name('token.generate.post');

    Route::get('/tokens', [UserTokenController::class, 'index'])
        ->name('tokens.index');

    Route::get('/tokens/{token}/edit', [UserTokenController::class, 'edit'])
        ->name('tokens.edit');

    Route::put('/tokens/{token}', [UserTokenController::class, 'update'])
        ->name('tokens.update');

    Route::delete('/tokens/{token}', [UserTokenController::class, 'destroy'])
        ->name('tokens.destroy');

    Route::post('/tokens/{token}/disable', [UserTokenController::class, 'disable'])
        ->name('tokens.disable');
});

Route::get('/admin/register', [AdminAuthController::class, 'showRegistrationForm'])
    ->name('admin.register');

Route::post('/admin/register', [AdminAuthController::class, 'register'])
    ->name('admin.register.post');

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.post');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

Route::get('/manager/login', [ManagerAuthController::class, 'showLoginForm'])
    ->name('manager.login');

Route::post('/manager/login', [ManagerAuthController::class, 'login'])
    ->name('manager.login.post');

Route::post('/manager/logout', [ManagerAuthController::class, 'logout'])
    ->name('manager.logout');

Route::middleware(ManagerMiddleware::class)->group(function () {
    Route::get('/manager/dashboard', [ManagerAuthController::class, 'dashboard'])
        ->name('manager.dashboard');

    Route::get('/manager/users', [ManagerAuthController::class, 'users'])
        ->name('manager.users.index');

    Route::post('/manager/users/{user}/disable', [ManagerAuthController::class, 'disable'])
        ->name('manager.users.disable');

    Route::post('/manager/users/{user}/enable', [ManagerAuthController::class, 'enable'])
        ->name('manager.users.enable');
});

Route::middleware(AdminMiddleware::class)->group(function () {
    Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/status', [AdminAuthController::class, 'status'])
        ->name('admin.status');

    Route::get('/admin/requests', [AdminAuthController::class, 'requests'])
        ->name('admin.requests');

    Route::get('/admin/token/generate', [AdminAuthController::class, 'showTokenGenerateForm'])
        ->name('admin.token.generate');

    Route::post('/admin/token/generate', [AdminAuthController::class, 'generateToken'])
        ->name('admin.token.generate.post');

    Route::get('/admin/users', [AdminAuthController::class, 'users'])
        ->name('admin.users.index');

    Route::get('/admin/tokens', [AdminAuthController::class, 'tokens'])
        ->name('admin.tokens.index');

    Route::get('/admin/users/create', [AdminUserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/users', [AdminUserController::class, 'store'])
        ->name('admin.users.store');

    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])
        ->name('admin.users.edit');

    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])
        ->name('admin.users.update');

    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])
        ->name('admin.users.destroy');

    Route::post('/admin/users/{user}/status', [AdminUserController::class, 'toggleStatus'])
        ->name('admin.users.toggle-status');

    Route::get('/admin/managers', [AdminManagerController::class, 'index'])
        ->name('admin.managers.index');

    Route::get('/admin/managers/create', [AdminManagerController::class, 'create'])
        ->name('admin.managers.create');

    Route::get('/admin/managers/{manager}/edit', [AdminManagerController::class, 'edit'])
        ->name('admin.managers.edit');

    Route::put('/admin/managers/{manager}', [AdminManagerController::class, 'update'])
        ->name('admin.managers.update');

    Route::delete('/admin/managers/{manager}', [AdminManagerController::class, 'destroy'])
        ->name('admin.managers.destroy');

    Route::post('/admin/managers', [AdminManagerController::class, 'store'])
        ->name('admin.managers.store');
});