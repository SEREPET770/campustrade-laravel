<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class KategoriLokasiSeeder extends Seeder
{
  public function run(): void
  {
    $kategori = [
      ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Perangkat elektronik seperti laptop, hp, kamera'],
      ['nama_kategori' => 'Fashion', 'deskripsi' => 'Pakaian, sepatu, tas dan aksesoris'],
      ['nama_kategori' => 'Buku', 'deskripsi' => 'Buku kuliah, novel, dan referensi'],
      ['nama_kategori' => 'Alat Tulis', 'deskripsi' => 'Peralatan tulis dan stationery'],
      ['nama_kategori' => 'Olahraga', 'deskripsi' => 'Peralatan dan perlengkapan olahraga'],
    ];

    foreach ($kategori as $k) {
      Kategori::firstOrCreate(['nama_kategori' => $k['nama_kategori']], $k);
    }

    $lokasi = [
      'Institut Teknologi Sepuluh Nopember (ITS)',
      'Institut Teknologi Adhi Tama Surabaya (ITATS)',
      'Politeknik Elektronika Negeri Surabaya (PENS)',
      'Politeknik Perkapalan Negeri Surabaya (PPNS)',
      'Universitas Airlangga (UNAIR) Kampus A',
      'Universitas Airlangga (UNAIR) Kampus B',
      'Universitas Airlangga (UNAIR) Kampus C',
      'Universitas Negeri Surabaya (UNESA) Ketintang',
      'Universitas Negeri Surabaya (UNESA) Lidah Wetan',
      'UPN Veteran Jawa Timur',
      'Universitas Surabaya (UBAYA)',
      'Universitas Kristen Petra',
      'Universitas Ciputra',
      'UIN Sunan Ampel Surabaya',
      'Universitas 17 Agustus 1945 Surabaya (UNTAG)',
      'Universitas Dinamika (STIKOM Surabaya)',
      'Universitas Narotama',
      'Universitas Dr. Soetomo (UNITOMO)',
      'Universitas Nahdlatul Ulama Surabaya (UNUSA)',
      'Universitas Muhammadiyah Surabaya',
    ];

    foreach ($lokasi as $nama) {
      Lokasi::firstOrCreate(['nama_lokasi' => $nama]);
    }
  }
}
