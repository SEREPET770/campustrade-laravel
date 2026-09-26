<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id_user');
      $table->string('nama', 100);
      $table->string('email', 100)->unique();
      $table->string('password', 255);
      $table->string('nim', 20)->unique();
      $table->string('no_whatsapp', 20);
      $table->text('alamat')->nullable();
      $table->string('kota', 100)->nullable();
      $table->string('kode_pos', 10)->nullable();
      $table->string('foto_ktm')->nullable();
      $table->enum('role', ['admin', 'user'])->default('user');
      $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
      $table->boolean('status_aktif')->default(true);
      $table->timestamp('created_at')->nullable()->useCurrent();
      $table->dateTime('terakhir_online')->nullable();
      $table->decimal('latitude', 10, 8)->nullable();
      $table->decimal('longitude', 11, 8)->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
