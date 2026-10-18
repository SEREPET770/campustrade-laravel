<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pesanan extends Model
{
  protected $table = 'pesanan';
  protected $primaryKey = 'id_pesanan';

  protected $fillable = [
    'id_produk',
    'id_pembeli',
    'id_penjual',
    'nama_produk',
    'harga',
    'ongkir',
    'total',
    'metode_pengiriman',
    'alamat_temu',
    'catatan_pembeli',
    'catatan_penjual',
    'status',
  ];

  /** Label dan kelas badge per status. */
  public const STATUS = [
    'menunggu' => ['Menunggu konfirmasi', 'pj-badge-warning'],
    'dikonfirmasi' => ['Dikonfirmasi', 'pj-badge-info'],
    'dikirim' => ['Dalam pengiriman', 'pj-badge-info'],
    'selesai' => ['Selesai', 'pj-badge-success'],
    'ditolak' => ['Ditolak penjual', 'pj-badge-danger'],
    'dibatalkan' => ['Dibatalkan', 'pj-badge-muted'],
  ];

  /** Status pesanan yang masih berjalan. */
  public const AKTIF = ['menunggu', 'dikonfirmasi', 'dikirim'];

  /** Transisi yang diizinkan: dari => [ke, ...]. */
  public const TRANSISI = [
    'menunggu' => ['dikonfirmasi', 'ditolak', 'dibatalkan'],
    'dikonfirmasi' => ['dikirim', 'dibatalkan'],
    'dikirim' => ['selesai', 'dibatalkan'],
  ];

  /** Status produk yang menyertai status tujuan pesanan. */
  private const STATUS_PRODUK = [
    'ditolak' => 'tersedia',
    'dibatalkan' => 'tersedia',
    'selesai' => 'terjual',
  ];

  protected function casts(): array
  {
    return [
      'harga' => 'decimal:2',
      'ongkir' => 'decimal:2',
      'total' => 'decimal:2',
    ];
  }

  public function produk()
  {
    return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
  }

  public function pembeli()
  {
    return $this->belongsTo(User::class, 'id_pembeli', 'id_user');
  }

  public function penjual()
  {
    return $this->belongsTo(User::class, 'id_penjual', 'id_user');
  }

  /**
   * Ubah status dengan aman: baris pesanan dikunci, transisi divalidasi,
   * dan status produk ikut disinkronkan dalam satu transaksi.
   *
   * @param  array<int, string>|null  $hanyaDari  Batasi status asal yang boleh (cek ulang di dalam kunci).
   */
  public function ubahStatus(string $ke, ?string $catatanPenjual = null, ?array $hanyaDari = null): bool
  {
    return DB::transaction(function () use ($ke, $catatanPenjual, $hanyaDari) {
      $terkini = static::lockForUpdate()->find($this->id_pesanan);

      if (! $terkini) {
        return false;
      }

      if ($hanyaDari !== null && ! in_array($terkini->status, $hanyaDari, true)) {
        return false;
      }

      if (! in_array($ke, self::TRANSISI[$terkini->status] ?? [], true)) {
        return false;
      }

      $terkini->status = $ke;
      if ($catatanPenjual !== null) {
        $terkini->catatan_penjual = $catatanPenjual;
      }
      $terkini->save();

      if (isset(self::STATUS_PRODUK[$ke]) && $terkini->id_produk) {
        Produk::where('id_produk', $terkini->id_produk)
          ->where('status_produk', 'dipesan')
          ->update(['status_produk' => self::STATUS_PRODUK[$ke]]);
      }

      $this->setRawAttributes($terkini->getAttributes(), true);

      return true;
    });
  }

  public function getKodeAttribute(): string
  {
    return 'CT-' . str_pad((string) $this->id_pesanan, 6, '0', STR_PAD_LEFT);
  }

  public function getWaPembeliAttribute(): ?string
  {
    $nomor = preg_replace('/\D/', '', (string) $this->pembeli?->no_whatsapp);
    if ($nomor === '') {
      return null;
    }
    if (str_starts_with($nomor, '0')) {
      $nomor = '62' . substr($nomor, 1);
    } elseif (str_starts_with($nomor, '8')) {
      $nomor = '62' . $nomor;
    }

    $pesan = rawurlencode('Halo, saya penjual di CampusTrade. Terkait pesanan ' . $this->kode . ' untuk "' . $this->nama_produk . '".');

    return "https://wa.me/{$nomor}?text={$pesan}";
  }

  public function getLabelStatusAttribute(): string
  {
    return self::STATUS[$this->status][0] ?? ucfirst((string) $this->status);
  }

  public function getKelasStatusAttribute(): string
  {
    return self::STATUS[$this->status][1] ?? 'pj-badge-muted';
  }

  public function getHargaFormatAttribute(): string
  {
    return self::rupiah($this->harga);
  }

  public function getOngkirFormatAttribute(): string
  {
    return (float) $this->ongkir > 0 ? self::rupiah($this->ongkir) : 'Gratis';
  }

  public function getTotalFormatAttribute(): string
  {
    return self::rupiah($this->total);
  }

  private static function rupiah($nilai): string
  {
    return 'Rp ' . number_format((float) $nilai, 0, ',', '.');
  }
}
