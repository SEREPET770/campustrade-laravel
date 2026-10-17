<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\LogVerifikasi;
use App\Models\Produk;
use App\Models\User;

class DashboardController extends Controller
{
  public function index()
  {
    $totalUser = User::where('role', 'user')->count();
    $menunggu = User::where('role', 'user')->where('status_verifikasi', 'menunggu')->count();

    $totalProduk = Produk::count();
    $produkTersedia = Produk::tersedia()->count();
    $produkTerjual = Produk::where('status_produk', 'terjual')->count();

    $jumlahKategori = Kategori::count();
    $jumlahLokasi = Lokasi::count();

    // Diurutkan lewat id (bukan created_at) karena data lama bisa punya created_at NULL
    $penggunaTerbaru = User::where('role', 'user')->orderByDesc('id_user')->limit(5)->get();
    $produkTerbaru = Produk::with(['kategori', 'gambar'])->orderByDesc('id_produk')->limit(5)->get();
    $aktivitas = LogVerifikasi::with(['pengguna', 'admin'])->orderByDesc('id_verifikasi')->limit(5)->get();

    // Diisi setelah modul Pesanan COD dibuat, contoh: ['total' => 0, 'menunggu' => 0, 'diterima' => 0, 'selesai' => 0]
    $ringkasanPesanan = null;

    return view('dashboard.admin', compact(
      'totalUser',
      'menunggu',
      'totalProduk',
      'produkTersedia',
      'produkTerjual',
      'jumlahKategori',
      'jumlahLokasi',
      'penggunaTerbaru',
      'produkTerbaru',
      'aktivitas',
      'ringkasanPesanan'
    ));
  }
}
