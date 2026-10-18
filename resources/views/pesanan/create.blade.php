@extends('layouts.app')
@section('title', 'Buat Pesanan')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $total = (float) $produk->harga + (float) $produk->ongkir;
        $labelAlamat = $produk->metode_pengiriman === 'Ambil di Tempat' ? 'Titik temu di kampus' : 'Alamat pengiriman';
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('produk.show', $produk) }}" class="pj-back">← Kembali ke produk</a>
                <h1>Buat Pesanan</h1>
                <p>Pembayaran dilakukan tunai (COD) saat barang diterima.</p>
            </div>
        </div>

        <div class="pj-two">
            <form method="POST" action="{{ route('pesanan.store', $produk) }}" class="pj-card pj-form"
                style="grid-template-columns:1fr;">
                @csrf

                <div class="pj-field">
                    <label for="alamat_temu">{{ $labelAlamat }} <span class="pj-req">*</span></label>
                    <textarea id="alamat_temu" name="alamat_temu" required maxlength="500"
                        placeholder="Contoh: Depan perpustakaan kampus, Senin–Jumat setelah jam 15.00">{{ old('alamat_temu') }}</textarea>
                    @error('alamat_temu')
                        <small class="pj-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="pj-field">
                    <label for="catatan_pembeli">Catatan untuk penjual <span class="pj-hint">(opsional)</span></label>
                    <textarea id="catatan_pembeli" name="catatan_pembeli" maxlength="1000">{{ old('catatan_pembeli') }}</textarea>
                    @error('catatan_pembeli')
                        <small class="pj-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="pj-form-actions">
                    <a href="{{ route('produk.show', $produk) }}" class="pj-btn pj-btn-ghost">Batal</a>
                    <button type="submit" class="pj-btn pj-btn-primary">Buat Pesanan</button>
                </div>
            </form>

            <div class="pj-card">
                <div class="pj-summary">
                    @if ($produk->foto_utama)
                        <img src="{{ $produk->foto_utama }}" alt="" class="pj-thumb">
                    @else
                        <div class="pj-thumb pj-thumb-empty">Tanpa foto</div>
                    @endif
                    <div>
                        <div class="pj-item-name">{{ $produk->nama_produk }}</div>
                        <div class="pj-item-sub">{{ $produk->kondisi }} · {{ $produk->lokasi->nama_lokasi ?? '-' }}</div>
                        <div class="pj-item-sub">Penjual: {{ $produk->penjual->nama ?? '-' }}</div>
                    </div>
                </div>

                <dl class="pj-rows" style="margin-top:18px;">
                    <dt>Harga</dt>
                    <dd>{{ $produk->harga_format }}</dd>
                    <dt>Pengiriman</dt>
                    <dd>{{ $produk->metode_pengiriman }}</dd>
                    <dt>Ongkir</dt>
                    <dd>{{ $produk->ongkir_format }}</dd>
                    <dt class="pj-total-dt">Total (bayar di tempat)</dt>
                    <dd class="pj-total-dd">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
