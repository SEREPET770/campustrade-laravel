<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserVerificationController;
use App\Http\Controllers\ProdukController;

Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::view('/tentang', 'about.index')->name('about');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard.user'))->name('user.dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard.admin'))->name('dashboard');
    Route::get('/pengguna', [UserVerificationController::class, 'index'])->name('pengguna.index');
    Route::patch('/pengguna/{user}/approve', [UserVerificationController::class, 'approve'])->name('pengguna.approve');
    Route::patch('/pengguna/{user}/reject', [UserVerificationController::class, 'reject'])->name('pengguna.reject');
});
