<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    if (! Schema::hasColumn('log_verifikasi', 'id_produk')) {
      Schema::table('log_verifikasi', function (Blueprint $table) {
        $table->unsignedBigInteger('id_produk')->nullable()->after('id_user')->index();
      });
    }
  }

  public function down(): void
  {
    if (Schema::hasColumn('log_verifikasi', 'id_produk')) {
      Schema::table('log_verifikasi', function (Blueprint $table) {
        $table->dropIndex(['id_produk']);
        $table->dropColumn('id_produk');
      });
    }
  }
};
