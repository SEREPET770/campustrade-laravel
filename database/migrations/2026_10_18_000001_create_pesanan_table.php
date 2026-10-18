<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('pesanan', function (Blueprint $table) {
      $table->id('id_pesanan');
      $table->unsignedBigInteger('id_produk')->nullable();
      $table->unsignedBigInteger('id_pembeli');
      $table->unsignedBigInteger('id_penjual');

      // Salinan data produk saat dipesan, agar riwayat tetap utuh
      $table->string('nama_produk', 150);
      $table->decimal('harga', 12, 2);
      $table->decimal('ongkir', 10, 2)->default(0);
      $table->decimal('total', 12, 2);
      $table->string('metode_pengiriman', 30);

      $table->text('alamat_temu');                  // titik temu atau alamat pengiriman
      $table->text('catatan_pembeli')->nullable();
      $table->text('catatan_penjual')->nullable();  // alasan tolak/batal dari penjual
      $table->enum('status', ['menunggu', 'dikonfirmasi', 'dikirim', 'selesai', 'ditolak', 'dibatalkan'])
        ->default('menunggu');
      $table->timestamps();

      $table->foreign('id_produk')->references('id_produk')->on('produk')->nullOnDelete();
      $table->foreign('id_pembeli')->references('id_user')->on('users')->restrictOnDelete();
      $table->foreign('id_penjual')->references('id_user')->on('users')->restrictOnDelete();

      $table->index(['id_pembeli', 'status']);
      $table->index(['id_penjual', 'status']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pesanan');
  }
};
