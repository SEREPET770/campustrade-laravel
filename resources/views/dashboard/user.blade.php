@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrap">
        <div class="page-header">
            <h1>Halo, {{ auth()->user()->nama }} 👋</h1>
            <p>Status verifikasi akun: <strong>{{ auth()->user()->status_verifikasi }}</strong></p>
        </div>
        <div class="card">Modul produk & transaksi akan menyusul di sini.</div>
    </div>
@endsection
