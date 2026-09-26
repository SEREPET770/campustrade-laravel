<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogVerifikasi extends Model
{
  protected $table = 'log_verifikasi';
  protected $primaryKey = 'id_verifikasi';
  public $timestamps = false;
  protected $fillable = ['id_user', 'verified_by', 'status', 'catatan'];
}
