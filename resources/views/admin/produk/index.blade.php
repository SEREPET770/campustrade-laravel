@extends('layouts.app')
@section('title', 'Verifikasi Produk | CampusTrade Admin')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $tab = [
            'menunggu' => 'Menunggu',
            'tersedia' => 'Tersedia',
            'ditolak' => 'Ditolak',
            'dipesan' => 'Dipesan',
            'terjual' => 'Terjual',
            'semua' => 'Semua',
        ];
        $badge = [
            'menunggu' => 'pj-badge-warning',
            'tersedia' => 'pj-badge-success',
            'dipesan' => 'pj-badge-info',
            'terjual' => 'pj-badge-muted',
            'ditolak' => 'pj-badge-danger',
        ];
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="pj-back">← Dashboard</a>
                <h1>Verifikasi Produk</h1>
                <p>Periksa produk yang diunggah penjual sebelum tampil di katalog</p>
            </div>
        </div>

        <div class="pj-tabs">
            @foreach ($tab as $key => $label)
                @php $total = $key === 'semua' ? $jumlah->sum() : ($jumlah[$key] ?? 0); @endphp
                <a href="{{ route('admin.produk.index', ['status' => $key]) }}"
                    class="pj-tab {{ $status === $key ? 'pj-tab-active' : '' }}">
                    {{ $label }} <span class="pj-count">{{ $total }}</span>
                </a>
            @endforeach
        </div>

        <div class="pj-table-wrap">
            <table class="pj-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Penjual</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Diajukan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produk as $item)
                        <tr>
                            <td>
                                <div class="pj-item">
                                    @if ($item->foto_utama)
                                        <img src="{{ $item->foto_utama }}" alt="" class="pj-thumb">
                                    @else
                                        <div class="pj-thumb pj-thumb-empty">Tanpa foto</div>
                                    @endif
                                    <div>
                                        <div class="pj-item-name">{{ $item->nama_produk }}</div>
                                        <div class="pj-item-sub">{{ $item->kondisi }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $item->penjual->nama ?? '-' }}
                                <div class="pj-item-sub">{{ $item->penjual->nim ?? '' }}</div>
                            </td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->harga_format }}</td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td><span
                                    class="pj-badge {{ $badge[$item->status_produk] ?? 'pj-badge-muted' }}">{{ ucfirst($item->status_produk) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.produk.show', $item) }}"
                                    class="pj-btn pj-btn-ghost pj-btn-sm">Periksa</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="pj-empty">Tidak ada produk pada status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $produk->links('pagination::bootstrap-4') }}
    </div>
@endsection
