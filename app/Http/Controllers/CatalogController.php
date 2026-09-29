<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
  public function index(Request $request)
  {
    $kategoriList = Kategori::withCount(['produk' => fn($q) => $q->tersedia()])
      ->orderBy('nama_kategori')
      ->get();

    $produkQuery = Produk::tersedia()->with(['kategori', 'lokasi', 'gambar', 'penjual']);

    if ($request->filled('search')) {
      $produkQuery->where('nama_produk', 'like', '%' . $request->string('search') . '%');
    }

    if ($request->filled('kategori')) {
      $produkQuery->where('id_kategori', $request->integer('kategori'));
    }

    $produkTerbaru = (clone $produkQuery)->orderByDesc('created_at')->limit(8)->get();
    $produkPilihan = (clone $produkQuery)->orderByDesc('harga')->limit(4)->get();

    return view('catalog.index', [
      'kategoriList' => $kategoriList,
      'produkTerbaru' => $produkTerbaru,
      'produkPilihan' => $produkPilihan,
      'search' => $request->input('search', ''),
      'kategoriAktif' => $request->integer('kategori'),
      'ctaJualLink' => Auth::check() ? route('user.dashboard') : route('register'),
      'ctaJualText' => Auth::check() ? 'Jual Barang Sekarang' : 'Daftar Sekarang',
    ]);
  }
}
