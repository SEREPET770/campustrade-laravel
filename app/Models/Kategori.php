<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
  protected $table = 'kategori_barang';
  protected $primaryKey = 'id_kategori';
  public $timestamps = false;

  protected $fillable = ['nama_kategori', 'deskripsi'];

  public function produk()
  {
    return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori');
  }

  public function icon(): string
  {
    return match ($this->nama_kategori) {
      'Elektronik' => '<rect x="4" y="4" width="16" height="11" rx="1.5"/><path d="M2 18h20l-2-3H4l-2 3z"/>',
      'Fashion' => '<path d="M8 4l4 2 4-2 4 4-3 3v10H7V11L4 8z"/>',
      'Buku' => '<path d="M4 5a2 2 0 0 1 2-2h6v18H6a2 2 0 0 0-2 2V5z"/><path d="M20 5a2 2 0 0 0-2-2h-6v18h6a2 2 0 0 1 2 2V5z"/>',
      'Alat Tulis' => '<path d="M4 20l1-5L16 4l4 4L9 19l-5 1z"/><path d="M13 6l4 4"/>',
      'Olahraga' => '<rect x="2" y="10" width="4" height="4" rx="1"/><rect x="18" y="10" width="4" height="4" rx="1"/><path d="M6 12h12"/><rect x="7" y="8" width="2" height="8" rx="1"/><rect x="15" y="8" width="2" height="8" rx="1"/>',
      default => '<path d="M20 12l-8 8-9-9V4h7l9 9z"/><circle cx="7.5" cy="7.5" r="1.2"/>',
    };
  }
}
