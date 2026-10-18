@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $pesananMasuk = \App\Models\Pesanan::where('id_penjual', auth()->id())
            ->where('status', 'menunggu')
            ->count();
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <h1>Halo, {{ auth()->user()->nama }} 👋</h1>
                <p>Status verifikasi akun: <strong>{{ auth()->user()->status_verifikasi }}</strong></p>
            </div>
        </div>

        <div class="pj-cards">
            <a href="{{ route('pesanan.index') }}" class="pj-card pj-link-card">
                <strong>Pesanan Saya</strong>
                <span>Pantau pesanan COD yang Anda buat</span>
            </a>
            <a href="{{ route('penjual.pesanan.index') }}" class="pj-card pj-link-card">
                <strong>
                    Pesanan Masuk
                    @if ($pesananMasuk > 0)
                        <span class="pj-badge pj-badge-warning">{{ $pesananMasuk }} baru</span>
                    @endif
                </strong>
                <span>Konfirmasi dan kelola pesanan dari pembeli</span>
            </a>
            <a href="{{ route('penjual.produk.create') }}" class="pj-card pj-link-card">
                <strong>+ Tambah Produk</strong>
                <span>Unggah barang baru untuk dijual</span>
            </a>
            <a href="{{ route('catalog.index') }}" class="pj-card pj-link-card">
                <strong>Lihat Katalog</strong>
                <span>Telusuri produk mahasiswa lain</span>
            </a>
        </div>
    </div>
@endsection
