@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <h1>Halo, {{ auth()->user()->nama }} 👋</h1>
                <p>Status verifikasi akun: <strong>{{ auth()->user()->status_verifikasi }}</strong></p>
            </div>
        </div>

        <div class="pj-cards">
            <a href="{{ route('penjual.produk.index') }}" class="pj-card pj-link-card">
                <strong>Produk Saya</strong>
                <span>Kelola produk yang Anda jual</span>
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
