<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/users', [UserController::class, 'getUsers']);

Route::post('/users', [UserController::class, 'store']);

Route::put('/users/{id}', [UserController::class, 'update']);

Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/currencies', [CurrencyController::class, 'getCurrencies']);

Route::get('/history', [HistoryController::class, 'getHistory']);
Route::get('/wallet', [WalletController::class, 'getWallet']);
Route::get('/transaction', [TransactionController::class, 'getTransaction']);

Route::get('/currencies/names', [CurrencyController::class, 'index']);

//state
Route::get('/status', [TransactionController::class, 'status']);

