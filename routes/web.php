<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Penjual\ProdukController as PenjualProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\Penjual\PesananController as PenjualPesananController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserVerificationController;
use App\Http\Controllers\Admin\ProdukVerificationController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
Route::view('/tentang', 'about.index')->name('about');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard.user'))->name('user.dashboard');
    Route::get('/produk/{produk}/pesan', [PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/produk/{produk}/pesan', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{pesanan}/batalkan', [PesananController::class, 'batalkan'])->name('pesanan.batalkan');

    Route::prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/produk', [PenjualProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/tambah', [PenjualProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [PenjualProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/ubah', [PenjualProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [PenjualProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [PenjualProdukController::class, 'destroy'])->name('produk.destroy');

        Route::get('/pesanan', [PenjualPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [PenjualPesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{pesanan}/{aksi}', [PenjualPesananController::class, 'aksi'])
            ->where('aksi', 'konfirmasi|tolak|kirim|selesai|batalkan')
            ->name('pesanan.aksi');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pengguna', [UserVerificationController::class, 'index'])->name('pengguna.index');
    Route::patch('/pengguna/{user}/approve', [UserVerificationController::class, 'approve'])->name('pengguna.approve');
    Route::patch('/pengguna/{user}/reject', [UserVerificationController::class, 'reject'])->name('pengguna.reject');
    Route::get('/produk', [ProdukVerificationController::class, 'index'])->name('produk.index');
    Route::get('/produk/{produk}', [ProdukVerificationController::class, 'show'])->name('produk.show');
    Route::patch('/produk/{produk}/approve', [ProdukVerificationController::class, 'approve'])->name('produk.approve');
    Route::patch('/produk/{produk}/reject', [ProdukVerificationController::class, 'reject'])->name('produk.reject');
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [AdminPesananController::class, 'show'])->name('pesanan.show');
});
