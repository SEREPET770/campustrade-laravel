<?php

namespace Database\Seeders;

use App\Models\GambarProduk;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProdukSeeder extends Seeder
{
  public function run(): void
  {
    // Kategori asli dari database native
    $kategori = [
      ['Elektronik', 'Perangkat elektronik seperti laptop, hp, kamera'],
      ['Fashion', 'Pakaian, sepatu, tas dan aksesoris'],
      ['Buku', 'Buku kuliah, novel, dan referensi'],
      ['Alat Tulis', 'Peralatan tulis dan stationery'],
      ['Olahraga', 'Peralatan dan perlengkapan olahraga'],
    ];
    foreach ($kategori as [$nama, $deskripsi]) {
      Kategori::firstOrCreate(['nama_kategori' => $nama], ['deskripsi' => $deskripsi]);
    }

    // Penjual (sudah terverifikasi). Password semua: password
    $daftarPenjual = [
      ['Rizky Aditya',     'rizky@campustrade.test',  '3121600001', '081234560001'],
      ['Dinda Salsabila',  'dinda@campustrade.test',  '3121600002', '081234560002'],
      ['Fajar Maulana',    'fajar@campustrade.test',  '3121600003', '081234560003'],
      ['Nabila Rahma',     'nabila@campustrade.test', '3121600004', '081234560004'],
    ];
    $penjual = [];
    foreach ($daftarPenjual as [$nama, $email, $nim, $wa]) {
      $penjual[] = User::firstOrCreate(['email' => $email], [
        'nama' => $nama,
        'password' => Hash::make('password'),
        'nim' => $nim,
        'no_whatsapp' => $wa,
        'alamat' => 'Surabaya',
        'kota' => 'Surabaya',
        'kode_pos' => '60111',
        'role' => 'user',
        'status_verifikasi' => 'terverifikasi',
        'status_aktif' => true,
      ]);
    }

    // [penjual(0-3), kategori, kata kunci lokasi, nama, deskripsi, harga, kondisi, brand, penulis/penerbit,
    //  pengiriman, alasan jual, kelebihan, kekurangan, status, umur (hari), nama file foto (opsional)]
    $produk = [
      [0, 'Elektronik', 'Sepuluh Nopember', 'Laptop Asus X441U', 'Core i3 gen 6, RAM 4GB, HDD 500GB. Baterai masih tahan sekitar 2 jam.', 2500000, 'Bekas', 'Asus', null, 'Ambil di Tempat', 'Sudah upgrade ke laptop baru', 'Performa lancar untuk tugas kuliah', 'Ada baret halus di bodi', 'tersedia', 1, 'laptop-asus-x441u.jpg'],
      [1, 'Elektronik', 'Nahdlatul Ulama', 'iPhone 11 64GB', 'Warna hitam, battery health 84%, fullset dus dan charger.', 3800000, 'Bekas', 'Apple', null, 'Kurir Kampus', 'Ganti ke tipe lebih baru', 'Layar mulus, Face ID normal', 'Ada lecet tipis di frame', 'tersedia', 2, 'iphone-11-64gb.jpg'],
      [2, 'Elektronik', 'Elektronika Negeri', 'Headphone JBL Tune 500', 'Wired headphone, suara bass mantap, kabel masih utuh.', 320000, 'Bekas', 'JBL', null, 'Ambil di Tempat', 'Jarang digunakan', 'Nyaman dipakai lama', 'Busa telinga sedikit aus', 'tersedia', 3, 'headphone-jbl-tune-500.jpg'],
      [3, 'Elektronik', 'Ciputra', 'Keyboard Wireless Logitech K380', 'Bluetooth, bisa konek 3 perangkat, baterai awet.', 250000, 'Bekas', 'Logitech', null, 'Kurir Kampus', 'Pindah ke keyboard mekanik', 'Ringkas dan senyap', 'Tidak ada backlight', 'tersedia', 4, 'keyboard-logitech-k380.jpg'],

      [1, 'Fashion', 'Ketintang', 'Vans Old Skool', 'Ukuran 42, warna hitam-putih, sol masih tebal.', 350000, 'Bekas', 'Vans', null, 'Ambil di Tempat', 'Ukuran tidak cocok', 'Awet dan mudah dipadukan', 'Ada noda kecil di sisi kiri', 'tersedia', 1, 'vans-old-skool.jpg'],
      [3, 'Fashion', 'Airlangga (UNAIR) Kampus C', 'Tas Eiger 25L', 'Tas ransel muat laptop 14", ada rain cover.', 250000, 'Bekas', 'Eiger', null, 'Ambil di Tempat', 'Sudah punya tas baru', 'Banyak kompartemen', 'Resleting kecil agak seret', 'tersedia', 2, 'tas-eiger-25l.jpg'],
      [0, 'Fashion', 'UPN Veteran', 'Jaket Denim Uniqlo', 'Ukuran L, warna biru tua, bahan tebal.', 175000, 'Bekas', 'Uniqlo', null, 'Kurir Kampus', 'Sudah kekecilan', 'Bahan bagus dan tidak luntur', null, 'tersedia', 5, 'jaket-denim-uniqlo.jpg'],
      [2, 'Fashion', 'Petra', 'Kemeja Flanel Kotak', 'Ukuran M, warna merah-hitam, nyaman dipakai.', 90000, 'Bekas', 'Cotton On', null, 'Ambil di Tempat', 'Jarang dipakai', null, null, 'tersedia', 6, 'kemeja-flanel.jpg'],

      [2, 'Buku', 'Ketintang', 'Buku Sistem Informasi Manajemen', 'Edisi 12, kondisi bersih tanpa coretan.', 75000, 'Bekas', null, 'Kenneth C. Laudon / Salemba Empat', 'Ambil di Tempat', 'Mata kuliah sudah selesai', 'Tidak ada halaman hilang', 'Sampul sedikit menguning', 'tersedia', 1, 'buku-sistem-informasi.jpg'],
      [0, 'Buku', 'Sepuluh Nopember', 'Buku Pemrograman Web', 'Membahas PHP, MySQL, dan Laravel dasar.', 60000, 'Bekas', null, 'Abdul Kadir / Andi Publisher', 'Kurir Kampus', 'Sudah lulus mata kuliah', 'Contoh kode lengkap', null, 'tersedia', 3, 'buku-pemrograman-web.jpg'],
      [3, 'Buku', 'UNITOMO', 'Kalkulus Jilid 1', 'Edisi 9, ada sedikit stabilo di beberapa bab.', 85000, 'Bekas', null, 'Purcell / Erlangga', 'Ambil di Tempat', 'Sudah tidak dipakai', 'Pembahasan runtut', 'Ada highlight', 'tersedia', 7, 'buku-kalkulus.jpg'],
      [1, 'Buku', 'Airlangga (UNAIR) Kampus B', 'Novel Laskar Pelangi', 'Kondisi baik, halaman lengkap.', 40000, 'Bekas', null, 'Andrea Hirata / Bentang Pustaka', 'Ekspedisi', 'Sudah selesai dibaca', null, null, 'tersedia', 8, 'novel-laskar-pelangi.jpg'],

      [2, 'Alat Tulis', 'Ketintang', 'Kalkulator Casio fx-991EX', 'Kalkulator ilmiah, layar jernih, fungsi normal semua.', 180000, 'Bekas', 'Casio', null, 'Ambil di Tempat', 'Sudah tidak dibutuhkan', 'Cocok untuk kuliah teknik', null, 'tersedia', 2, 'kalkulator-casio.jpg'],
      [0, 'Alat Tulis', 'Perkapalan', 'Set Alat Gambar Teknik Rotring', 'Isi mistar, jangka, drawing pen 0.1-0.8.', 150000, 'Baru', 'Rotring', null, 'Kurir Kampus', 'Salah beli', 'Masih tersegel', null, 'tersedia', 4, 'set-gambar-rotring.jpg'],
      [3, 'Alat Tulis', 'Ciputra', 'Drawing Pen Snowman Set 6', 'Ukuran 0.05 sampai 0.8, tinta masih penuh.', 45000, 'Baru', 'Snowman', null, 'Ambil di Tempat', 'Kelebihan stok', null, null, 'tersedia', 9, 'drawing-pen-snowman.jpg'],

      [1, 'Olahraga', 'Sepuluh Nopember', 'Raket Badminton Yonex Astrox', 'Grip baru diganti, senar masih kencang, dapat cover.', 300000, 'Bekas', 'Yonex', null, 'Ambil di Tempat', 'Jarang main', 'Ringan dan seimbang', null, 'tersedia', 3, 'raket-yonex-astrox.jpg'],
      [2, 'Olahraga', 'UBAYA', 'Sepatu Futsal Specs', 'Ukuran 41, sol karet masih tebal.', 200000, 'Bekas', 'Specs', null, 'Ambil di Tempat', 'Sudah tidak ikut futsal', 'Grip bagus di lapangan indoor', null, 'tersedia', 5, 'sepatu-futsal-specs.jpg'],
      [0, 'Olahraga', 'Muhammadiyah', 'Matras Yoga 6mm', 'Bahan TPE anti slip, sekalian tas jinjing.', 60000, 'Baru', 'Decathlon', null, 'Kurir Kampus', 'Dapat hadiah, tidak terpakai', null, null, 'tersedia', 10, 'matras-yoga.jpg'],

      [1, 'Elektronik', 'Lidah Wetan', 'Monitor LG 22 inch 22MK430H', 'IPS, HDMI + VGA, tanpa dead pixel. Cocok untuk setup kos.', 850000, 'Bekas', 'LG', null, 'Ambil di Tempat', 'Pindah ke monitor ultrawide', 'Warna akurat dan tipis', 'Tidak ada speaker bawaan', 'tersedia', 2, 'monitor-lg-22.jpg'],
      [2, 'Elektronik', 'Sepuluh Nopember', 'Mouse Logitech M331 Silent', 'Wireless, klik senyap, baterai baru diganti.', 90000, 'Bekas', 'Logitech', null, 'Kurir Kampus', 'Sudah ada mouse baru', 'Tidak berisik di perpustakaan', null, 'tersedia', 4, 'mouse-logitech-m331.jpg'],
      [0, 'Elektronik', 'Dinamika', 'Kamera Canon EOS M10', 'Mirrorless, lensa kit 15-45mm, shutter count rendah, ada 2 baterai.', 2800000, 'Bekas', 'Canon', null, 'Ambil di Tempat', 'Beralih ke kamera lain', 'Ringan dan hasil foto tajam', 'Tidak ada viewfinder', 'tersedia', 6, 'kamera-canon-eos-m10.jpg'],

      [3, 'Fashion', 'Sunan Ampel', 'Sepatu Nike Air Force 1', 'Ukuran 43, warna putih, sudah dibersihkan.', 450000, 'Bekas', 'Nike', null, 'Ambil di Tempat', 'Ukuran kebesaran', 'Kulit masih bagus', 'Sol sedikit menguning', 'tersedia', 3, 'sepatu-nike-af1.jpg'],
      [1, 'Fashion', 'Narotama', 'Jam Tangan Casio Vintage', 'Digital, anti air, baterai baru diganti bulan lalu.', 250000, 'Bekas', 'Casio', null, 'Kurir Kampus', 'Koleksi berlebih', 'Gaya retro', 'Ada goresan halus di kaca', 'tersedia', 7, 'jam-casio-vintage.jpg'],

      [2, 'Buku', 'Sepuluh Nopember', 'Buku Algoritma dan Struktur Data', 'Cocok untuk mahasiswa informatika, catatan pensil sedikit.', 55000, 'Bekas', null, 'Rinaldi Munir / Informatika', 'Ambil di Tempat', 'Mata kuliah sudah lewat', 'Contoh kode mudah diikuti', 'Ada coretan pensil', 'tersedia', 5, 'buku-algoritma.jpg'],
      [3, 'Buku', 'UNTAG', 'Atomic Habits (Bahasa Indonesia)', 'Kondisi seperti baru, hanya sekali dibaca.', 70000, 'Bekas', null, 'James Clear / Gramedia Pustaka Utama', 'Ekspedisi', 'Sudah selesai dibaca', 'Kondisi mulus', null, 'tersedia', 9, 'buku-atomic-habits.jpg'],

      [0, 'Olahraga', 'Ketintang', 'Bola Basket Molten GG7X', 'Ukuran 7, grip masih bagus, sudah dipompa.', 250000, 'Bekas', 'Molten', null, 'Ambil di Tempat', 'Sudah tidak ikut UKM basket', 'Pantulan stabil', null, 'tersedia', 8, 'bola-basket-molten.jpg'],
      [1, 'Olahraga', 'Petra', 'Dumbbell Set 10kg', 'Sepasang 5kg, bisa diatur bebannya, bahan vinyl.', 150000, 'Baru', 'Kettler', null, 'Kurir Kampus', 'Tidak jadi rutin gym', 'Cocok untuk latihan di kos', null, 'tersedia', 12, 'dumbbell-set-10kg.jpg'],

      // Contoh produk non-tersedia: tidak akan muncul di katalog publik
      [3, 'Elektronik', 'Ketintang', 'Powerbank Anker 10000mAh', 'Sudah dipesan pembeli.', 200000, 'Bekas', 'Anker', null, 'Ambil di Tempat', 'Upgrade kapasitas', null, null, 'dipesan', 11, null],
    ];

    foreach ($produk as $p) {
      [
        $idx,
        $kat,
        $lokasi,
        $nama,
        $deskripsi,
        $harga,
        $kondisi,
        $brand,
        $penulis,
        $kirim,
        $alasan,
        $lebih,
        $kurang,
        $status,
        $umur,
        $foto
      ] = $p;

      $sudahAda = Produk::where('id_user', $penjual[$idx]->id_user)->where('nama_produk', $nama)->first();

      $record = $sudahAda ?? Produk::forceCreate([
        'id_user' => $penjual[$idx]->id_user,
        'id_kategori' => Kategori::where('nama_kategori', $kat)->value('id_kategori'),
        'id_lokasi' => Lokasi::where('nama_lokasi', 'like', "%{$lokasi}%")->value('id_lokasi'),
        'nama_produk' => $nama,
        'deskripsi' => $deskripsi,
        'harga' => $harga,
        'ongkir' => match ($kirim) {
          'Kurir Kampus' => 5000,
          'Ekspedisi' => 15000,
          default => 0
        },
        'metode_pengiriman' => $kirim,
        'kondisi' => $kondisi,
        'alasan_jual' => $alasan,
        'status_produk' => $status,
        'status_aktif' => true,
        'kelebihan' => $lebih,
        'kekurangan' => $kurang,
        'brand' => $brand,
        'penulis_penerbit' => $penulis,
        'status_tayang' => 'aktif',
        'created_at' => now()->subDays($umur),
      ]);

      // Foto dipasang hanya kalau filenya memang ada di storage/app/public/produk/
      if ($foto && Storage::disk('public')->exists('produk/' . $foto)) {
        GambarProduk::firstOrCreate(['id_produk' => $record->id_produk, 'image_path' => $foto]);
      }
    }
  }
}
