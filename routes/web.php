<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AduanController;

// Rute untuk menampilkan form aduan
Route::middleware(['auth'])->group(function () {
    Route::get('/aduan/create', function () {
        return view('aduan.create');
    })->name('aduan.create');

    Route::post('/aduan/store', [AduanController::class, 'store'])->name('aduan.store');
});

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk menyimpan aduan (hanya bisa diakses jika user sudah login)
Route::middleware(['auth'])->group(function () {
    Route::post('/aduan/store', [AduanController::class, 'store'])->name('aduan.store');
});