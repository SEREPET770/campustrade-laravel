@extends('layouts.app')
@section('title', 'Admin Dashboard')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <h1>Admin Dashboard</h1>
                <p>Ringkasan pengelolaan CampusTrade</p>
            </div>
        </div>

        <div class="pj-cards">
            <a href="{{ route('admin.pengguna.index') }}" class="pj-card pj-link-card">
                <strong>Kelola Pengguna</strong>
                <span>Verifikasi KTM dan kelola akun</span>
            </a>
            <a href="{{ route('admin.produk.index') }}" class="pj-card pj-link-card">
                <strong>Verifikasi Produk</strong>
                <span>Setujui atau tolak produk baru</span>
            </a>
        </div>
    </div>
@endsection
