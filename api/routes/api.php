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

// Route to get the authenticated user's information
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route for user login
Route::post('/login', [AuthController::class, 'login']);

// Route for user logout
Route::post('/logout', [AuthController::class, 'logout']);

// Route to get all users
Route::get('/users', [UserController::class, 'getUsers']);

// Route to create a new user
Route::post('/users', [UserController::class, 'store']);

// Route to update user details
Route::put('/users/{id}', [UserController::class, 'update']);

// Route to delete a user
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Route to get all available currencies
Route::get('/currencies', [CurrencyController::class, 'getCurrencies']);

// Route to get user transaction history
Route::get('/history', [HistoryController::class, 'getHistory']);

// Route to get user wallet information
Route::get('/wallet', [WalletController::class, 'getWallet']);

// Route to get user transaction information
Route::get('/transaction', [TransactionController::class, 'getTransaction']);

// Route to get currency names only
Route::get('/currencies/names', [CurrencyController::class, 'index']);

// Route to get status
Route::get('/status', [TransactionController::class, 'status']);

// Route to retrieve transactions for a specific user
Route::get('user-transactions/{userId}', [TransactionController::class, 'getUserTransactions']);

// Route to sell crypto for ID
Route::delete('/sell-crypto/{id}', [TransactionController::class, 'sellCrypto']);

// Route to update user details
Route::put('/users/{id}', [UserController::class, 'update']);
