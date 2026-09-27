<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('produk', function (Blueprint $table) {
      $table->id('id_produk');
      $table->unsignedBigInteger('id_user');
      $table->unsignedBigInteger('id_kategori');
      $table->unsignedBigInteger('id_lokasi')->nullable();
      $table->decimal('latitude', 10, 8)->nullable();
      $table->decimal('longitude', 11, 8)->nullable();
      $table->string('nama_produk', 150);
      $table->text('deskripsi');
      $table->decimal('harga', 12, 2);
      $table->decimal('ongkir', 10, 2)->default(0);
      $table->enum('metode_pengiriman', ['Ambil di Tempat', 'Kurir Kampus', 'Ekspedisi'])->default('Ambil di Tempat');
      $table->enum('kondisi', ['Baru', 'Bekas']);
      $table->text('alasan_jual')->nullable();
      $table->enum('status_produk', ['menunggu', 'tersedia', 'dipesan', 'terjual', 'ditolak'])->default('menunggu');
      $table->boolean('status_aktif')->default(true);
      $table->timestamp('created_at')->useCurrent();
      $table->text('kelebihan')->nullable();
      $table->text('kekurangan')->nullable();
      $table->string('brand', 100)->nullable();
      $table->string('penulis_penerbit')->nullable();
      $table->enum('status_tayang', ['belum_bayar', 'aktif'])->default('belum_bayar');

      $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
      $table->foreign('id_kategori')->references('id_kategori')->on('kategori_barang')->onDelete('cascade');
      $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('produk');
  }
};
