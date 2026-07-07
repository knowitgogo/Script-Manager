<?php

use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/tokens', [TokenController::class, 'store'])->name('api.tokens.store');
    Route::get('/tokens', [TokenController::class, 'index'])->name('api.tokens.index');
});
