@extends('layouts.app')
@section('title', 'Pesanan Saya')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $tab = [
            'semua' => 'Semua',
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan / Ditolak',
        ];
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('user.dashboard') }}" class="pj-back">← Dashboard</a>
                <h1>Pesanan Saya</h1>
                <p>Pantau pesanan COD yang Anda buat</p>
            </div>
            <a href="{{ route('produk.index') }}" class="pj-btn pj-btn-primary">Cari Produk</a>
        </div>

        <div class="pj-tabs">
            @foreach ($tab as $key => $label)
                <a href="{{ route('pesanan.index', ['status' => $key]) }}"
                    class="pj-tab {{ $filter === $key ? 'pj-tab-active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="pj-table-wrap">
            <table class="pj-table">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        <th>Penjual</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $p)
                        <tr>
                            <td>
                                <div class="pj-item">
                                    @if ($p->produk?->foto_utama)
                                        <img src="{{ $p->produk->foto_utama }}" alt="" class="pj-thumb">
                                    @else
                                        <div class="pj-thumb pj-thumb-empty">Tanpa foto</div>
                                    @endif
                                    <div>
                                        <div class="pj-item-name">{{ $p->nama_produk }}</div>
                                        <div class="pj-item-sub">{{ $p->kode }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->penjual->nama ?? '-' }}</td>
                            <td>{{ $p->total_format }}</td>
                            <td><span class="pj-badge {{ $p->kelas_status }}">{{ $p->label_status }}</span></td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('pesanan.show', $p) }}" class="pj-btn pj-btn-ghost pj-btn-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pj-empty">Belum ada pesanan pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pesanan->links('pagination::bootstrap-4') }}
    </div>
@endsection
