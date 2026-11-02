<?php

namespace App\Http\Controllers;

use App\Models\LogVerifikasi;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function index(): View|RedirectResponse
  {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->isAdmin()) {
      return redirect()->route('admin.dashboard');
    }

    $id = $user->getKey();
    $awalBulan = now()->startOfMonth();
    $pesananSaya = fn() => Pesanan::where('id_penjual', $id);
    $produkSaya = fn() => Produk::where('id_user', $id);

    $ringkasan = [
      'totalProduk' => $produkSaya()->count(),
      'produkBaruBulanIni' => $produkSaya()->where('created_at', '>=', $awalBulan)->count(),
      'produkTerjual' => $produkSaya()->where('status_produk', 'terjual')->count(),
      'terjualBulanIni' => $pesananSaya()->where('status', 'selesai')->where('updated_at', '>=', $awalBulan)->count(),
      'pesananBaru' => $pesananSaya()->where('status', 'menunggu')->count(),
      'pesananHariIni' => $pesananSaya()->where('created_at', '>=', now()->startOfDay())->count(),
      // Pendapatan = harga barang dari pesanan selesai (tanpa ongkir).
      'pendapatan' => (float) $pesananSaya()->where('status', 'selesai')->sum('harga'),
      'pendapatanBulanIni' => (float) $pesananSaya()->where('status', 'selesai')
        ->where('updated_at', '>=', $awalBulan)->sum('harga'),
    ];

    $perStatus = $pesananSaya()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
    $donut = [
      ['label' => 'Dikonfirmasi', 'value' => (int) ($perStatus['dikonfirmasi'] ?? 0), 'color' => '#1d6fa5'],
      ['label' => 'Dalam pengiriman', 'value' => (int) ($perStatus['dikirim'] ?? 0), 'color' => '#6c5ce7'],
      ['label' => 'Selesai', 'value' => (int) ($perStatus['selesai'] ?? 0), 'color' => '#007c85'],
    ];

    // Grafik 7 hari terakhir.
    $mulai = now()->subDays(6)->startOfDay();
    $masuk = $pesananSaya()->where('created_at', '>=', $mulai)
      ->selectRaw('DATE(created_at) as tgl, COUNT(*) as total')->groupBy('tgl')->pluck('total', 'tgl');
    $terjual = $pesananSaya()->where('status', 'selesai')->where('updated_at', '>=', $mulai)
      ->selectRaw('DATE(updated_at) as tgl, COUNT(*) as total')->groupBy('tgl')->pluck('total', 'tgl');

    $grafik = ['labels' => [], 'masuk' => [], 'terjual' => []];
    for ($i = 0; $i < 7; $i++) {
      $hari = $mulai->copy()->addDays($i);
      $kunci = $hari->toDateString();
      $grafik['labels'][] = $hari->locale('id')->translatedFormat('d M');
      $grafik['masuk'][] = (int) ($masuk[$kunci] ?? 0);
      $grafik['terjual'][] = (int) ($terjual[$kunci] ?? 0);
    }

    // Aktivitas toko: perubahan pesanan + keputusan admin atas produk.
    $dariPesanan = $pesananSaya()->with('pembeli')->orderByDesc('updated_at')->limit(5)->get()
      ->map(function (Pesanan $p) {
        $nama = $p->pembeli->nama ?? 'pembeli';
        [$ikon, $nada, $teks] = match ($p->status) {
          'menunggu' => ['fa-cart-shopping', 'is-new', "Pesanan baru {$p->kode} dari {$nama}"],
          'dikonfirmasi' => ['fa-circle-check', 'is-ok', "Pesanan {$p->kode} dikonfirmasi"],
          'dikirim' => ['fa-truck', 'is-info', "Pesanan {$p->kode} dalam pengiriman"],
          'selesai' => ['fa-flag-checkered', 'is-ok', "Pesanan {$p->kode} selesai"],
          'ditolak' => ['fa-ban', 'is-no', "Pesanan {$p->kode} ditolak"],
          default => ['fa-xmark', 'is-no', "Pesanan {$p->kode} dibatalkan"],
        };

        return ['waktu' => $p->updated_at, 'ikon' => $ikon, 'nada' => $nada, 'teks' => $teks];
      });

    $dariLog = LogVerifikasi::with('produk')
      ->where('id_user', $id)->whereNotNull('id_produk')
      ->orderByDesc('id_verifikasi')->limit(5)->get()
      ->map(fn($l) => [
        'waktu' => $l->created_at,
        'ikon' => $l->status === 'terverifikasi' ? 'fa-circle-check' : 'fa-ban',
        'nada' => $l->status === 'terverifikasi' ? 'is-ok' : 'is-no',
        'teks' => 'Produk ' . ($l->produk->nama_produk ?? 'yang dihapus')
          . ($l->status === 'terverifikasi' ? ' disetujui admin' : ' ditolak admin'),
      ]);

    $aktivitas = $dariPesanan->concat($dariLog)
      ->sortByDesc(fn($a) => $a['waktu']?->timestamp ?? 0)
      ->take(5)
      ->values();

    return view('dashboard.user', [
      'ringkasan' => $ringkasan,
      'donut' => $donut,
      'grafik' => $grafik,
      'produkTerbaru' => $produkSaya()->with(['kategori', 'gambar'])->orderByDesc('id_produk')->limit(5)->get(),
      'pesananTerbaru' => $pesananSaya()->with('pembeli')->orderByDesc('id_pesanan')->limit(5)->get(),
      'aktivitas' => $aktivitas,
    ]);
  }
}
