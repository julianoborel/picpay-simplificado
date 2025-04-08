<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

Route::prefix('v1')->group(function () {
    // Rotas CRUD completas para usuários
    Route::apiResource('users', UserController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

    // Rotas de transferência
    Route::post('/transfer', [TransactionController::class, 'store']);
});

// Rota de exemplo com autenticação (se necessário)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
