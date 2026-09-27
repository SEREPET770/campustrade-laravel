<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('gambar_produk', function (Blueprint $table) {
      $table->id('id_gambar');
      $table->unsignedBigInteger('id_produk');
      $table->string('image_path');

      $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('gambar_produk');
  }
};
