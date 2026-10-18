@extends('layouts.admin')
@section('title', 'Pesanan ' . $pesanan->kode . ' | Admin')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('admin.pesanan.index') }}" class="pj-back">← Pesanan</a>
                <h1>Pesanan {{ $pesanan->kode }}</h1>
                <p>
                    <span class="pj-badge {{ $pesanan->kelas_status }}">{{ $pesanan->label_status }}</span>
                    · dibuat {{ $pesanan->created_at->format('d/m/Y H:i') }}
                    · diperbarui {{ $pesanan->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <div class="pj-two">
            <div class="pj-card">
                <h2 class="pj-section-title">Rincian pesanan</h2>
                <dl class="pj-dl">
                    <dt>Produk</dt>
                    <dd>{{ $pesanan->nama_produk }}</dd>
                    <dt>Harga</dt>
                    <dd>{{ $pesanan->harga_format }}</dd>
                    <dt>Pengiriman</dt>
                    <dd>{{ $pesanan->metode_pengiriman }}</dd>
                    <dt>Ongkir</dt>
                    <dd>{{ $pesanan->ongkir_format }}</dd>
                    <dt>Total (COD)</dt>
                    <dd><strong>{{ $pesanan->total_format }}</strong></dd>
                    <dt>{{ $pesanan->metode_pengiriman === 'Ambil di Tempat' ? 'Titik temu' : 'Alamat' }}</dt>
                    <dd>{{ $pesanan->alamat_temu }}</dd>
                    <dt>Catatan pembeli</dt>
                    <dd>{{ $pesanan->catatan_pembeli ?: '-' }}</dd>
                    <dt>Catatan penjual</dt>
                    <dd>{{ $pesanan->catatan_penjual ?: '-' }}</dd>
                </dl>
            </div>

            <div class="pj-stack">
                <div class="pj-card">
                    <h2 class="pj-section-title">Pembeli</h2>
                    <dl class="pj-dl">
                        <dt>Nama</dt>
                        <dd>{{ $pesanan->pembeli->nama ?? '-' }}</dd>
                        <dt>NIM</dt>
                        <dd>{{ $pesanan->pembeli->nim ?? '-' }}</dd>
                        <dt>WhatsApp</dt>
                        <dd>{{ $pesanan->pembeli->no_whatsapp ?? '-' }}</dd>
                    </dl>
                </div>
                <div class="pj-card">
                    <h2 class="pj-section-title">Penjual</h2>
                    <dl class="pj-dl">
                        <dt>Nama</dt>
                        <dd>{{ $pesanan->penjual->nama ?? '-' }}</dd>
                        <dt>NIM</dt>
                        <dd>{{ $pesanan->penjual->nim ?? '-' }}</dd>
                        <dt>WhatsApp</dt>
                        <dd>{{ $pesanan->penjual->no_whatsapp ?? '-' }}</dd>
                    </dl>
                </div>
                <p class="pj-note">Admin hanya memantau pesanan. Perubahan status dilakukan oleh penjual.</p>
            </div>
        </div>
    </div>
@endsection
