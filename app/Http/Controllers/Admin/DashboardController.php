<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogVerifikasi;
use App\Models\Lokasi;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
  private const HARI_GRAFIK = 90;

  public function index(): View
  {
    $awalBulan = now()->startOfMonth();
    $pengguna = fn() => User::where('role', 'user');

    $totalUser = $pengguna()->count();
    $menunggu = $pengguna()->where('status_verifikasi', 'menunggu')->count();
    $terverifikasi = $pengguna()->where('status_verifikasi', 'terverifikasi')->count();
    $ditolak = $pengguna()->where('status_verifikasi', 'ditolak')->count();

    $donut = [
      ['label' => 'Terverifikasi', 'value' => $terverifikasi, 'color' => '#10a37f'],
      ['label' => 'Menunggu', 'value' => $menunggu, 'color' => '#f59e0b'],
      ['label' => 'Ditolak', 'value' => $ditolak, 'color' => '#ef4444'],
    ];

    // Grafik 90 hari (tab 7/30/90 hari memotong dari data yang sama di browser).
    $mulai = now()->subDays(self::HARI_GRAFIK - 1)->startOfDay();
    $hitungPerHari = fn($query, string $kolom) => $query
      ->where($kolom, '>=', $mulai)
      ->selectRaw("DATE({$kolom}) as tgl, COUNT(*) as total")
      ->groupBy('tgl')
      ->pluck('total', 'tgl');

    $penggunaBaru = $hitungPerHari($pengguna(), 'created_at');
    $pesananBaru = $hitungPerHari(Pesanan::query(), 'created_at');

    $grafik = ['labels' => [], 'pengguna' => [], 'pesanan' => []];
    for ($i = 0; $i < self::HARI_GRAFIK; $i++) {
      $hari = $mulai->copy()->addDays($i);
      $kunci = $hari->toDateString();
      $grafik['labels'][] = $hari->locale('id')->translatedFormat('d M');
      $grafik['pengguna'][] = (int) ($penggunaBaru[$kunci] ?? 0);
      $grafik['pesanan'][] = (int) ($pesananBaru[$kunci] ?? 0);
    }

    $perStatus = Pesanan::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
    $ringkasanPesanan = [
      'total' => (int) $perStatus->sum(),
      'menunggu' => (int) ($perStatus['menunggu'] ?? 0),
      'diproses' => (int) (($perStatus['dikonfirmasi'] ?? 0) + ($perStatus['dikirim'] ?? 0)),
      'selesai' => (int) ($perStatus['selesai'] ?? 0),
    ];

    return view('dashboard.admin', [
      'totalUser' => $totalUser,
      'menunggu' => $menunggu,
      'penggunaBulanIni' => $pengguna()->where('created_at', '>=', $awalBulan)->count(),
      'produkTersedia' => Produk::tersedia()->count(),
      'produkBaruBulanIni' => Produk::where('created_at', '>=', $awalBulan)->count(),
      'produkTerjual' => Produk::where('status_produk', 'terjual')->count(),
      'terjualBulanIni' => Pesanan::where('status', 'selesai')->where('updated_at', '>=', $awalBulan)->count(),
      'totalKategori' => Kategori::count(),
      'totalLokasi' => Lokasi::count(),
      'donut' => $donut,
      'grafik' => $grafik,
      'ringkasanPesanan' => $ringkasanPesanan,
      'penggunaTerbaru' => $pengguna()->orderByDesc('id_user')->limit(5)->get(),
      'produkTerbaru' => Produk::with(['kategori', 'gambar'])->orderByDesc('id_produk')->limit(5)->get(),
      'aktivitas' => LogVerifikasi::with(['pengguna', 'admin', 'produk'])
        ->orderByDesc('id_verifikasi')
        ->limit(5)
        ->get(),
    ]);
  }
}
