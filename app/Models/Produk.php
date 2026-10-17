<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
  protected $table = 'produk';
  protected $primaryKey = 'id_produk';
  const UPDATED_AT = null; // tabel produk cuma punya created_at, tanpa updated_at

  protected $fillable = [
    'id_user',
    'id_kategori',
    'id_lokasi',
    'latitude',
    'longitude',
    'nama_produk',
    'deskripsi',
    'harga',
    'ongkir',
    'metode_pengiriman',
    'kondisi',
    'alasan_jual',
    'status_produk',
    'status_aktif',
    'kelebihan',
    'kekurangan',
    'brand',
    'penulis_penerbit',
  ];

  protected function casts(): array
  {
    return [
      'harga' => 'decimal:2',
      'ongkir' => 'decimal:2',
      'status_aktif' => 'boolean',
      'created_at' => 'datetime',
    ];
  }

  public function kategori()
  {
    return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
  }

  public function lokasi()
  {
    return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
  }

  public function penjual()
  {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  public function gambar()
  {
    return $this->hasMany(GambarProduk::class, 'id_produk', 'id_produk');
  }

  public function logVerifikasi()
  {
    return $this->hasMany(LogVerifikasi::class, 'id_produk', 'id_produk');
  }

  public function scopeTersedia($query)
  {
    return $query->where('status_produk', 'tersedia')
      ->where('status_aktif', true);
  }

  public function getHargaFormatAttribute(): string
  {
    return 'Rp ' . number_format((float) $this->harga, 0, ',', '.');
  }

  public function getFotoUtamaAttribute(): ?string
  {
    $gambar = $this->gambar->first();
    return $gambar ? asset('storage/produk/' . $gambar->image_path) : null;
  }
  public function getFotoUrlsAttribute(): array
  {
    return $this->gambar
      ->map(fn($g) => asset('storage/produk/' . $g->image_path))
      ->all();
  }

  public function getOngkirFormatAttribute(): string
  {
    return (float) $this->ongkir > 0
      ? 'Rp ' . number_format((float) $this->ongkir, 0, ',', '.')
      : 'Gratis';
  }

  public function getKelebihanListAttribute(): array
  {
    return self::pecahBaris($this->kelebihan);
  }

  public function getKekuranganListAttribute(): array
  {
    return self::pecahBaris($this->kekurangan);
  }

  public function getWhatsappLinkAttribute(): ?string
  {
    $nomor = preg_replace('/\D/', '', (string) $this->penjual?->no_whatsapp);
    if ($nomor === '') {
      return null;
    }
    if (str_starts_with($nomor, '0')) {
      $nomor = '62' . substr($nomor, 1);
    } elseif (str_starts_with($nomor, '8')) {
      $nomor = '62' . $nomor;
    }

    $pesan = rawurlencode('Halo, saya tertarik dengan "' . $this->nama_produk . '" di CampusTrade.');

    return "https://wa.me/{$nomor}?text={$pesan}";
  }

  protected static function pecahBaris(?string $teks): array
  {
    return array_values(array_filter(
      array_map('trim', preg_split('/\r\n|\r|\n/', (string) $teks))
    ));
  }
}
