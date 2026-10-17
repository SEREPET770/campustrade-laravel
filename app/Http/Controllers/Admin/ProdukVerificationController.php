<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogVerifikasi;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProdukVerificationController extends Controller
{
  private const STATUS = ['menunggu', 'tersedia', 'ditolak', 'dipesan', 'terjual'];

  public function index(Request $request): View
  {
    $status = $request->input('status', 'menunggu');
    if (! in_array($status, self::STATUS, true)) {
      $status = 'semua';
    }

    $query = Produk::with(['kategori', 'gambar', 'penjual']);

    if ($status !== 'semua') {
      $query->where('status_produk', $status);
    }

    // Antrian verifikasi: yang paling lama menunggu didahulukan.
    $status === 'menunggu'
      ? $query->orderBy('created_at')
      : $query->orderByDesc('created_at');

    return view('admin.produk.index', [
      'produk' => $query->paginate(15)->withQueryString(),
      'status' => $status,
      'jumlah' => Produk::selectRaw('status_produk, COUNT(*) as total')
        ->groupBy('status_produk')
        ->pluck('total', 'status_produk'),
    ]);
  }

  public function show(Produk $produk): View
  {
    $produk->load(['kategori', 'lokasi', 'gambar', 'penjual']);

    $riwayat = $produk->logVerifikasi()
      ->with('admin')
      ->orderByDesc('id_verifikasi')
      ->get();

    return view('admin.produk.show', compact('produk', 'riwayat'));
  }

  public function approve(Produk $produk): RedirectResponse
  {
    return $this->putuskan(
      $produk,
      'tersedia',
      'terverifikasi',
      null,
      'Produk disetujui dan sekarang tampil di katalog.'
    );
  }

  public function reject(Request $request, Produk $produk): RedirectResponse
  {
    $data = $request->validate(
      ['catatan' => ['required', 'string', 'max:500']],
      ['catatan.required' => 'Tulis alasan penolakan agar penjual tahu apa yang harus diperbaiki.']
    );

    return $this->putuskan(
      $produk,
      'ditolak',
      'ditolak',
      $data['catatan'],
      'Produk ditolak. Penjual dapat memperbaiki lalu mengajukan ulang.'
    );
  }

  private function putuskan(
    Produk $produk,
    string $statusProduk,
    string $statusLog,
    ?string $catatan,
    string $pesanSukses
  ): RedirectResponse {
    $berhasil = DB::transaction(function () use ($produk, $statusProduk, $statusLog, $catatan) {
      // Kunci baris supaya dua admin tidak memutuskan produk yang sama bersamaan.
      $terkini = Produk::lockForUpdate()->find($produk->id_produk);

      if (! $terkini || $terkini->status_produk !== 'menunggu') {
        return false;
      }

      $terkini->update(['status_produk' => $statusProduk]);

      LogVerifikasi::create([
        'id_user' => $terkini->id_user, // pemilik produk
        'id_produk' => $terkini->id_produk,
        'verified_by' => Auth::id(),
        'status' => $statusLog,
        'catatan' => $catatan,
      ]);

      return true;
    });

    if (! $berhasil) {
      return redirect()->route('admin.produk.show', $produk)->with('notif', [
        'pesan' => 'Produk ini sudah diputuskan sebelumnya dan tidak lagi menunggu verifikasi.',
        'tipe' => 'warning',
      ]);
    }

    return redirect()->route('admin.produk.index')->with('notif', [
      'pesan' => $pesanSukses,
      'tipe' => $statusLog === 'terverifikasi' ? 'success' : 'warning',
    ]);
  }
}
