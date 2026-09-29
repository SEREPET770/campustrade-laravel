<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
  public function index(Request $request)
  {
    $baseTersedia = Produk::tersedia();

    $kategoriList = Kategori::withCount(['produk' => fn($q) => $q->tersedia()])
      ->orderBy('nama_kategori')
      ->get();

    $lokasiList = Lokasi::withCount(['produk' => fn($q) => $q->tersedia()])
      ->having('produk_count', '>', 0)
      ->orderBy('nama_lokasi')
      ->get();

    $jumlahSemua = (clone $baseTersedia)->count();
    $jumlahBaru = (clone $baseTersedia)->where('kondisi', 'Baru')->count();
    $jumlahBekas = (clone $baseTersedia)->where('kondisi', 'Bekas')->count();

    $search = trim((string) $request->input('search', ''));
    $kategoriDipilih = array_map('intval', (array) $request->input('kategori', []));
    $lokasiDipilih = array_map('intval', (array) $request->input('lokasi', []));
    $kondisi = in_array($request->input('kondisi'), ['Baru', 'Bekas']) ? $request->input('kondisi') : null;
    $hargaMin = $request->input('harga_min');
    $hargaMax = $request->input('harga_max');
    $urutkan = $request->input('urutkan', 'terbaru');

    $query = Produk::tersedia()->with(['kategori', 'lokasi', 'gambar', 'penjual']);

    if ($search !== '') {
      $query->where('nama_produk', 'like', '%' . $search . '%');
    }
    if ($kategoriDipilih) {
      $query->whereIn('id_kategori', $kategoriDipilih);
    }
    if ($lokasiDipilih) {
      $query->whereIn('id_lokasi', $lokasiDipilih);
    }
    if ($kondisi) {
      $query->where('kondisi', $kondisi);
    }
    if (is_numeric($hargaMin)) {
      $query->where('harga', '>=', (float) $hargaMin);
    }
    if (is_numeric($hargaMax)) {
      $query->where('harga', '<=', (float) $hargaMax);
    }

    match ($urutkan) {
      'harga_asc' => $query->orderBy('harga', 'asc'),
      'harga_desc' => $query->orderBy('harga', 'desc'),
      default => $query->orderByDesc('created_at'),
    };

    $produk = $query->paginate(12)->withQueryString();

    return view('produk.index', [
      'produk' => $produk,
      'kategoriList' => $kategoriList,
      'lokasiList' => $lokasiList,
      'jumlahSemua' => $jumlahSemua,
      'jumlahBaru' => $jumlahBaru,
      'jumlahBekas' => $jumlahBekas,
      'search' => $search,
      'kategoriDipilih' => $kategoriDipilih,
      'lokasiDipilih' => $lokasiDipilih,
      'kondisi' => $kondisi,
      'hargaMin' => $hargaMin,
      'hargaMax' => $hargaMax,
      'urutkan' => $urutkan,
    ]);
  }
}
