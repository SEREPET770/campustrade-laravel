<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogVerifikasi extends Model
{
  protected $table = 'log_verifikasi';
  protected $primaryKey = 'id_verifikasi';
  public $timestamps = false;
  protected $fillable = ['id_user', 'id_produk', 'verified_by', 'status', 'catatan'];

  protected function casts(): array
  {
    return ['created_at' => 'datetime'];
  }

  public function pengguna(): BelongsTo
  {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  public function admin(): BelongsTo
  {
    return $this->belongsTo(User::class, 'verified_by', 'id_user');
  }
}
