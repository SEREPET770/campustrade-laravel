<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\GambarProduk;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ProdukController extends Controller
{
  /** Status awal produk baru. Ganti ke 'tersedia' bila halaman verifikasi admin belum dipakai. */
  private const STATUS_AWAL = 'menunggu';

  private const MAKS_FOTO = 5;

  /** Ongkir otomatis per metode pengiriman (sama dengan ProdukSeeder). */
  private const ONGKIR = [
    'Ambil di Tempat' => 0,
    'Kurir Kampus' => 5000,
    'Ekspedisi' => 15000,
  ];

  public function index(): View
  {
    $produk = Produk::where('id_user', Auth::id())
      ->with(['kategori', 'gambar'])
      ->orderByDesc('created_at')
      ->paginate(10);

    return view('penjual.produk.index', compact('produk'));
  }

  public function create(): View
  {
    return $this->formView(new Produk([
      'kondisi' => 'Bekas',
      'metode_pengiriman' => 'Ambil di Tempat',
    ]));
  }

  public function store(Request $request): RedirectResponse
  {
    $data = $request->validate($this->rules() + [
      'foto' => ['required', 'array', 'min:1', 'max:' . self::MAKS_FOTO],
      'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ], $this->messages());

    DB::transaction(function () use ($request, $data) {
      $produk = Produk::create($this->atribut($data) + [
        'id_user' => Auth::id(),
        'status_produk' => self::STATUS_AWAL,
        'status_aktif' => true,
      ]);

      $this->simpanFoto($produk, $request->file('foto', []));
    });

    return redirect()->route('penjual.produk.index')->with('notif', [
      'pesan' => self::STATUS_AWAL === 'menunggu'
        ? 'Produk berhasil ditambahkan dan menunggu verifikasi admin.'
        : 'Produk berhasil ditambahkan.',
      'tipe' => 'success',
    ]);
  }

  public function edit(Produk $produk): View|RedirectResponse
  {
    $this->pastikanMilik($produk);

    if (! $this->bisaDiubah($produk)) {
      return $this->tolakUbah();
    }

    $produk->load('gambar');

    return $this->formView($produk);
  }

  public function update(Request $request, Produk $produk): RedirectResponse
  {
    $this->pastikanMilik($produk);

    if (! $this->bisaDiubah($produk)) {
      return $this->tolakUbah();
    }

    $produk->load('gambar');

    // Hanya foto milik produk ini yang bisa dihapus (difilter dari relasi, bukan dari input mentah).
    $hapusIds = array_map('intval', (array) $request->input('hapus_gambar', []));
    $gambarHapus = $produk->gambar->whereIn('id_gambar', $hapusIds);
    $sisa = $produk->gambar->count() - $gambarHapus->count();
    $ruangFoto = max(0, self::MAKS_FOTO - $sisa);

    $data = $request->validate($this->rules() + [
      'foto' => ['nullable', 'array', 'max:' . $ruangFoto],
      'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
      'hapus_gambar' => ['nullable', 'array'],
    ], $this->messages());

    $baru = $request->file('foto', []);

    if ($sisa + count($baru) < 1) {
      return back()->withInput()->withErrors(['foto' => 'Produk harus memiliki minimal 1 foto.']);
    }

    $atribut = $this->atribut($data);

    // Produk yang ditolak dianggap diajukan ulang setelah diperbaiki.
    if ($produk->status_produk === 'ditolak') {
      $atribut['status_produk'] = self::STATUS_AWAL;
    }

    DB::transaction(function () use ($produk, $atribut, $gambarHapus, $baru) {
      $produk->update($atribut);
      GambarProduk::whereIn('id_gambar', $gambarHapus->pluck('id_gambar')->all())->delete();
      $this->simpanFoto($produk, $baru);
    });

    // File lama baru dihapus setelah transaksi sukses, supaya kegagalan DB tidak menghilangkan foto.
    Storage::disk('public')->delete(
      $gambarHapus->map(fn($g) => 'produk/' . $g->image_path)->all()
    );

    return redirect()->route('penjual.produk.index')->with('notif', [
      'pesan' => 'Produk berhasil diperbarui.',
      'tipe' => 'success',
    ]);
  }

  public function destroy(Produk $produk): RedirectResponse
  {
    $this->pastikanMilik($produk);

    if (! $this->bisaDiubah($produk)) {
      return $this->tolakUbah();
    }

    $berkas = $produk->gambar->map(fn($g) => 'produk/' . $g->image_path)->all();

    $produk->delete(); // baris gambar_produk ikut terhapus lewat FK cascade
    Storage::disk('public')->delete($berkas);

    return redirect()->route('penjual.produk.index')->with('notif', [
      'pesan' => 'Produk berhasil dihapus.',
      'tipe' => 'success',
    ]);
  }

  /* ------------------------------------------------------------------ */

  private function formView(Produk $produk): View
  {
    return view('penjual.produk.form', [
      'produk' => $produk,
      'kategoriList' => Kategori::orderBy('nama_kategori')->get(),
      'lokasiList' => Lokasi::orderBy('nama_lokasi')->get(),
      'metodeList' => array_keys(self::ONGKIR),
      'ongkir' => self::ONGKIR,
      'maksFoto' => self::MAKS_FOTO,
    ]);
  }

  private function rules(): array
  {
    return [
      'nama_produk' => ['required', 'string', 'max:150'],
      'id_kategori' => ['required', 'integer', 'exists:kategori_barang,id_kategori'],
      'id_lokasi' => ['required', 'integer', 'exists:lokasi,id_lokasi'],
      'deskripsi' => ['required', 'string', 'max:5000'],
      'harga' => ['required', 'numeric', 'min:0', 'max:9999999999'],
      'kondisi' => ['required', Rule::in(['Baru', 'Bekas'])],
      'metode_pengiriman' => ['required', Rule::in(array_keys(self::ONGKIR))],
      'brand' => ['nullable', 'string', 'max:100'],
      'penulis_penerbit' => ['nullable', 'string', 'max:255'],
      'alasan_jual' => ['nullable', 'string', 'max:2000'],
      'kelebihan' => ['nullable', 'string', 'max:2000'],
      'kekurangan' => ['nullable', 'string', 'max:2000'],
    ];
  }

  private function messages(): array
  {
    return [
      'id_kategori.required' => 'Pilih kategori produk.',
      'id_lokasi.required' => 'Pilih lokasi kampus.',
      'foto.required' => 'Unggah minimal 1 foto produk.',
      'foto.min' => 'Unggah minimal 1 foto produk.',
      'foto.max' => 'Jumlah foto melebihi batas (maksimal ' . self::MAKS_FOTO . ' foto per produk).',
      'foto.*.image' => 'Berkas harus berupa gambar.',
      'foto.*.mimes' => 'Foto harus berformat JPG, PNG, atau WEBP.',
      'foto.*.max' => 'Ukuran tiap foto maksimal 2 MB.',
    ];
  }

  /** Atribut yang boleh masuk ke tabel produk (tanpa input foto), plus ongkir otomatis. */
  private function atribut(array $data): array
  {
    $atribut = Arr::except($data, ['foto', 'hapus_gambar']);
    $atribut['ongkir'] = self::ONGKIR[$data['metode_pengiriman']];

    return $atribut;
  }

  /** @param array<int, \Illuminate\Http\UploadedFile> $files */
  private function simpanFoto(Produk $produk, array $files): void
  {
    $tersimpan = [];

    try {
      foreach ($files as $file) {
        $nama = (string) Str::uuid() . '.' . $file->extension();
        $file->storeAs('produk', $nama, 'public');
        $tersimpan[] = 'produk/' . $nama;

        GambarProduk::create(['id_produk' => $produk->id_produk, 'image_path' => $nama]);
      }
    } catch (Throwable $e) {
      Storage::disk('public')->delete($tersimpan); // jangan tinggalkan file yatim
      throw $e;
    }
  }

  private function pastikanMilik(Produk $produk): void
  {
    abort_unless((int) $produk->id_user === (int) Auth::id(), 403);
  }

  private function bisaDiubah(Produk $produk): bool
  {
    return in_array($produk->status_produk, ['menunggu', 'tersedia', 'ditolak'], true);
  }

  private function tolakUbah(): RedirectResponse
  {
    return redirect()->route('penjual.produk.index')->with('notif', [
      'pesan' => 'Produk yang sedang dipesan atau sudah terjual tidak bisa diubah atau dihapus.',
      'tipe' => 'warning',
    ]);
  }
}
