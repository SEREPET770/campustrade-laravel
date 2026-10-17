@extends('layouts.app')
@section('title', $produk->exists ? 'Ubah Produk' : 'Tambah Produk')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php $edit = $produk->exists; @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('penjual.produk.index') }}" class="pj-back">← Produk Saya</a>
                <h1>{{ $edit ? 'Ubah Produk' : 'Tambah Produk' }}</h1>
                <p>Isi data produk selengkap mungkin agar pembeli yakin.</p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" class="pj-card pj-form"
            action="{{ $edit ? route('penjual.produk.update', $produk) : route('penjual.produk.store') }}">
            @csrf
            @if ($edit)
                @method('PUT')
            @endif

            @if ($edit && $produk->status_produk === 'ditolak')
                @php
                    $alasan = $produk
                        ->logVerifikasi()
                        ->where('status', 'ditolak')
                        ->orderByDesc('id_verifikasi')
                        ->value('catatan');
                @endphp
                <div class="pj-alert pj-alert-danger">
                    Produk ini ditolak admin.
                    @if ($alasan)
                        <strong>Alasan:</strong> {{ $alasan }}
                    @endif
                    Perbaiki datanya lalu simpan untuk mengajukan ulang.
                </div>
            @endif

            <div class="pj-field pj-col-2">
                <label for="nama_produk">Nama produk <span class="pj-req">*</span></label>
                <input type="text" id="nama_produk" name="nama_produk" maxlength="150" required
                    value="{{ old('nama_produk', $produk->nama_produk) }}">
                @error('nama_produk')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="id_kategori">Kategori <span class="pj-req">*</span></label>
                <select id="id_kategori" name="id_kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat->id_kategori }}" @selected(old('id_kategori', $produk->id_kategori) == $kat->id_kategori)>
                            {{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="id_lokasi">Lokasi kampus <span class="pj-req">*</span></label>
                <select id="id_lokasi" name="id_lokasi" required>
                    <option value="">Pilih lokasi</option>
                    @foreach ($lokasiList as $lok)
                        <option value="{{ $lok->id_lokasi }}" @selected(old('id_lokasi', $produk->id_lokasi) == $lok->id_lokasi)>
                            {{ $lok->nama_lokasi }}</option>
                    @endforeach
                </select>
                @error('id_lokasi')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="harga">Harga (Rp) <span class="pj-req">*</span></label>
                <input type="number" id="harga" name="harga" min="0" step="1" required
                    value="{{ old('harga', $edit ? (int) $produk->harga : '') }}">
                @error('harga')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="kondisi">Kondisi <span class="pj-req">*</span></label>
                <select id="kondisi" name="kondisi" required>
                    @foreach (['Baru', 'Bekas'] as $k)
                        <option value="{{ $k }}" @selected(old('kondisi', $produk->kondisi) === $k)>{{ $k }}</option>
                    @endforeach
                </select>
                @error('kondisi')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field pj-col-2">
                <label for="metode_pengiriman">Metode pengiriman <span class="pj-req">*</span></label>
                <select id="metode_pengiriman" name="metode_pengiriman" required>
                    @foreach ($metodeList as $m)
                        <option value="{{ $m }}" data-ongkir="{{ $ongkir[$m] }}"
                            @selected(old('metode_pengiriman', $produk->metode_pengiriman) === $m)>
                            {{ $m }}</option>
                    @endforeach
                </select>
                <small class="pj-hint" id="ongkirInfo"></small>
                @error('metode_pengiriman')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="brand">Merek <span class="pj-hint">(opsional)</span></label>
                <input type="text" id="brand" name="brand" maxlength="100"
                    value="{{ old('brand', $produk->brand) }}">
                @error('brand')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="penulis_penerbit">Penulis / penerbit <span class="pj-hint">(untuk buku, opsional)</span></label>
                <input type="text" id="penulis_penerbit" name="penulis_penerbit" maxlength="255"
                    value="{{ old('penulis_penerbit', $produk->penulis_penerbit) }}">
                @error('penulis_penerbit')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field pj-col-2">
                <label for="deskripsi">Deskripsi <span class="pj-req">*</span></label>
                <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                @error('deskripsi')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field pj-col-2">
                <label for="alasan_jual">Alasan dijual <span class="pj-hint">(opsional)</span></label>
                <textarea id="alasan_jual" name="alasan_jual">{{ old('alasan_jual', $produk->alasan_jual) }}</textarea>
                @error('alasan_jual')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="kelebihan">Kelebihan <span class="pj-hint">(opsional)</span></label>
                <textarea id="kelebihan" name="kelebihan">{{ old('kelebihan', $produk->kelebihan) }}</textarea>
                @error('kelebihan')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-field">
                <label for="kekurangan">Kekurangan <span class="pj-hint">(opsional)</span></label>
                <textarea id="kekurangan" name="kekurangan">{{ old('kekurangan', $produk->kekurangan) }}</textarea>
                @error('kekurangan')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            @if ($edit && $produk->gambar->isNotEmpty())
                <div class="pj-field pj-col-2">
                    <label>Foto saat ini <span class="pj-hint">(centang untuk menghapus)</span></label>
                    <div class="pj-gallery">
                        @foreach ($produk->gambar as $g)
                            <label class="pj-gallery-item">
                                <img src="{{ asset('storage/produk/' . $g->image_path) }}" alt="">
                                <span>
                                    <input type="checkbox" name="hapus_gambar[]" value="{{ $g->id_gambar }}"
                                        @checked(is_array(old('hapus_gambar')) && in_array($g->id_gambar, old('hapus_gambar')))>
                                    Hapus
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pj-field pj-col-2">
                <label for="foto">{{ $edit ? 'Tambah foto baru' : 'Foto produk' }}
                    @unless ($edit)
                        <span class="pj-req">*</span>
                    @endunless
                </label>
                <input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/webp" multiple
                    @required(!$edit)>
                <small class="pj-hint">JPG, PNG, atau WEBP, maksimal 2 MB per foto, total maksimal {{ $maksFoto }}
                    foto
                    per produk.</small>
                @error('foto')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
                @error('foto.*')
                    <small class="pj-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="pj-form-actions">
                <a href="{{ route('penjual.produk.index') }}" class="pj-btn pj-btn-ghost">Batal</a>
                <button type="submit" class="pj-btn pj-btn-primary">
                    {{ $edit ? 'Simpan Perubahan' : 'Ajukan Produk' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const metode = document.getElementById('metode_pengiriman');
        const info = document.getElementById('ongkirInfo');

        function updateOngkir() {
            const ongkir = Number(metode.selectedOptions[0].dataset.ongkir || 0);
            info.textContent = ongkir > 0 ?
                'Ongkir otomatis: Rp ' + ongkir.toLocaleString('id-ID') :
                'Tanpa ongkir';
        }

        metode.addEventListener('change', updateOngkir);
        updateOngkir();
    </script>
@endpush
