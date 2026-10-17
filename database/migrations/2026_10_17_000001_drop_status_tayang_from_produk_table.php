<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (Schema::hasColumn('produk', 'status_tayang')) {
      Schema::table('produk', function (Blueprint $table) {
        $table->dropColumn('status_tayang');
      });
    }
  }

  public function down(): void
  {
    if (! Schema::hasColumn('produk', 'status_tayang')) {
      Schema::table('produk', function (Blueprint $table) {
        $table->enum('status_tayang', ['belum_bayar', 'aktif'])->default('belum_bayar');
      });
    }
  }
};
