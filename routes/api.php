<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\Api\StockLogController; 
use App\Http\Controllers\Api\LibraryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make government fun!
|
*/

// Rute API untuk menyimpan aduan
Route::post('/aduan/store', [AduanController::class, 'store']);

// Rute API Resource untuk Kategori
Route::apiResource('categories', CategoryController::class);

// Produk routes
Route::apiResource('products', ProductController::class);

// 2. Rute Stock Logs diletakkan di luar (sejajar dengan rute produk & kategori)
Route::apiResource('stock-logs', StockLogController::class);

// --- RUTE PERPUSTAKAAN ---
Route::get('/library/books', [LibraryController::class, 'getBooks']);
Route::post('/library/books', [LibraryController::class, 'storeBook']);
Route::get('/library/loans', [LibraryController::class, 'getLoans']);
Route::post('/library/loans', [LibraryController::class, 'storeLoan']);

// Auth routes (Publik - Register & Login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Profile & Logout routes (Dilindungi sanctum)
Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Rute untuk mengambil data user yang sedang login via token Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});