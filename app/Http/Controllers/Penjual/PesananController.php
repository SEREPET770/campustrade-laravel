<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananController extends Controller
{
  /** Filter tab "Pesanan Masuk" → daftar status. */
  private const FILTER = [
    'menunggu' => ['menunggu'],
    'berjalan' => ['dikonfirmasi', 'dikirim'],
    'selesai' => ['selesai'],
    'batal' => ['ditolak', 'dibatalkan'],
  ];

  /**
   * Aksi penjual: nama => [status tujuan, status asal yang diizinkan, wajib alasan?, pesan sukses].
   */
  private const AKSI = [
    'konfirmasi' => ['dikonfirmasi', ['menunggu'], false, 'Pesanan dikonfirmasi.'],
    'tolak' => ['ditolak', ['menunggu'], true, 'Pesanan ditolak. Produk kembali tersedia.'],
    'kirim' => ['dikirim', ['dikonfirmasi'], false, 'Status diperbarui: pesanan dalam pengiriman.'],
    'selesai' => ['selesai', ['dikirim'], false, 'Pesanan selesai. Produk ditandai terjual.'],
    'batalkan' => ['dibatalkan', ['dikonfirmasi', 'dikirim'], true, 'Pesanan dibatalkan. Produk kembali tersedia.'],
  ];

  public function index(Request $request): View
  {
    $filter = $request->input('status', 'semua');

    $query = Pesanan::with(['produk.gambar', 'pembeli'])
      ->where('id_penjual', Auth::id());

    if (isset(self::FILTER[$filter])) {
      $query->whereIn('status', self::FILTER[$filter]);
    } else {
      $filter = 'semua';
    }

    // Pesanan yang menunggu konfirmasi didahulukan, lalu yang terbaru.
    $query->orderByRaw("status = 'menunggu' desc")->orderByDesc('id_pesanan');

    return view('penjual.pesanan.index', [
      'pesanan' => $query->paginate(10)->withQueryString(),
      'filter' => $filter,
      'jumlah' => Pesanan::where('id_penjual', Auth::id())
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status'),
    ]);
  }

  public function show(Pesanan $pesanan): View
  {
    $this->pastikanPenjual($pesanan);

    $pesanan->load(['produk.gambar', 'pembeli']);

    return view('penjual.pesanan.show', compact('pesanan'));
  }

  public function aksi(Request $request, Pesanan $pesanan, string $aksi): RedirectResponse
  {
    $this->pastikanPenjual($pesanan);
    abort_unless(isset(self::AKSI[$aksi]), 404);

    [$ke, $dari, $wajibAlasan, $pesanSukses] = self::AKSI[$aksi];

    $catatan = null;
    if ($wajibAlasan) {
      $catatan = $request->validate(
        ['catatan' => ['required', 'string', 'max:500']],
        ['catatan.required' => 'Tulis alasan agar pembeli tahu kenapa pesanan ini tidak dilanjutkan.']
      )['catatan'];
    }

    $berhasil = $pesanan->ubahStatus($ke, $catatan, $dari);

    return redirect()->route('penjual.pesanan.show', $pesanan)->with('notif', $berhasil
      ? ['pesan' => $pesanSukses, 'tipe' => 'success']
      : ['pesan' => 'Status pesanan sudah berubah. Muat ulang halaman ini.', 'tipe' => 'warning']);
  }

  private function pastikanPenjual(Pesanan $pesanan): void
  {
    abort_unless((int) $pesanan->id_penjual === (int) Auth::id(), 403);
  }
}
