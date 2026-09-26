<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('log_verifikasi', function (Blueprint $table) {
      $table->increments('id_verifikasi');
      $table->unsignedInteger('id_user');
      $table->unsignedInteger('verified_by');
      $table->enum('status', ['terverifikasi', 'ditolak']);
      $table->text('catatan')->nullable();
      $table->timestamp('created_at')->nullable()->useCurrent();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('log_verifikasi');
  }
};
