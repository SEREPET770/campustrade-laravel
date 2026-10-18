<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PesananController extends Controller
{
  /** Filter tab "Pesanan Saya" → daftar status. */
  private const FILTER = [
    'berjalan' => ['menunggu', 'dikonfirmasi', 'dikirim'],
    'selesai' => ['selesai'],
    'batal' => ['ditolak', 'dibatalkan'],
  ];

  public function create(Produk $produk): View|RedirectResponse
  {
    if ($alasan = $this->alasanTidakBisa($produk)) {
      return $this->tolak($alasan);
    }

    $produk->load(['gambar', 'lokasi', 'penjual']);

    return view('pesanan.create', compact('produk'));
  }

  public function store(Request $request, Produk $produk): RedirectResponse
  {
    $data = $request->validate([
      'alamat_temu' => ['required', 'string', 'max:500'],
      'catatan_pembeli' => ['nullable', 'string', 'max:1000'],
    ], [
      'alamat_temu.required' => 'Isi titik temu atau alamat pengiriman.',
    ]);

    $pesanan = DB::transaction(function () use ($produk, $data) {
      // Kunci baris produk supaya dua pembeli tidak memesan barang yang sama.
      $terkini = Produk::lockForUpdate()->find($produk->id_produk);

      if (! $terkini || $this->alasanTidakBisa($terkini)) {
        return null;
      }

      $pesanan = Pesanan::create([
        'id_produk' => $terkini->id_produk,
        'id_pembeli' => Auth::id(),
        'id_penjual' => $terkini->id_user,
        'nama_produk' => $terkini->nama_produk,
        'harga' => $terkini->harga,
        'ongkir' => $terkini->ongkir,
        'total' => round((float) $terkini->harga + (float) $terkini->ongkir, 2),
        'metode_pengiriman' => $terkini->metode_pengiriman,
        'alamat_temu' => $data['alamat_temu'],
        'catatan_pembeli' => $data['catatan_pembeli'] ?? null,
        'status' => 'menunggu',
      ]);

      $terkini->update(['status_produk' => 'dipesan']);

      return $pesanan;
    });

    if (! $pesanan) {
      return $this->tolak('Produk ini sudah tidak tersedia atau tidak bisa Anda pesan.');
    }

    return redirect()->route('pesanan.show', $pesanan)->with('notif', [
      'pesan' => 'Pesanan berhasil dibuat. Tunggu konfirmasi dari penjual.',
      'tipe' => 'success',
    ]);
  }

  public function index(Request $request): View
  {
    $filter = $request->input('status', 'semua');

    $query = Pesanan::with(['produk.gambar', 'penjual'])
      ->where('id_pembeli', Auth::id())
      ->orderByDesc('id_pesanan');

    if (isset(self::FILTER[$filter])) {
      $query->whereIn('status', self::FILTER[$filter]);
    } else {
      $filter = 'semua';
    }

    return view('pesanan.index', [
      'pesanan' => $query->paginate(10)->withQueryString(),
      'filter' => $filter,
    ]);
  }

  public function show(Pesanan $pesanan): View
  {
    $this->pastikanPembeli($pesanan);

    $pesanan->load(['produk.gambar', 'produk.penjual', 'penjual']);

    return view('pesanan.show', compact('pesanan'));
  }

  public function batalkan(Pesanan $pesanan): RedirectResponse
  {
    $this->pastikanPembeli($pesanan);

    // Pembeli hanya boleh membatalkan sebelum barang dikirim (dicek ulang di dalam kunci).
    $berhasil = $pesanan->ubahStatus('dibatalkan', null, ['menunggu', 'dikonfirmasi']);

    return redirect()->route('pesanan.show', $pesanan)->with('notif', $berhasil
      ? ['pesan' => 'Pesanan dibatalkan. Produk kembali tersedia.', 'tipe' => 'success']
      : ['pesan' => 'Pesanan ini sudah tidak bisa dibatalkan.', 'tipe' => 'warning']);
  }

  /* ------------------------------------------------------------------ */
  private function alasanTidakBisa(Produk $produk): ?string
  {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->isAdmin()) {
      return 'Akun admin tidak dapat memesan produk.';
    }

    if ((int) $produk->id_user === (int) Auth::id()) {
      return 'Anda tidak bisa memesan produk milik sendiri.';
    }

    if ($produk->status_produk !== 'tersedia' || ! $produk->status_aktif) {
      return 'Produk ini sudah tidak tersedia.';
    }

    return null;
  }

  private function tolak(string $pesan): RedirectResponse
  {
    return redirect()->route('produk.index')->with('notif', [
      'pesan' => $pesan,
      'tipe' => 'warning',
    ]);
  }

  private function pastikanPembeli(Pesanan $pesanan): void
  {
    abort_unless((int) $pesanan->id_pembeli === (int) Auth::id(), 403);
  }
}
