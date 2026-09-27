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
    'status_tayang',
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
}
