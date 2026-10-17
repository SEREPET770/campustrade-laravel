@extends('layouts.app')
@section('title', 'Produk Saya')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $status = [
            'menunggu' => ['Menunggu verifikasi', 'pj-badge-warning'],
            'tersedia' => ['Tersedia', 'pj-badge-success'],
            'dipesan' => ['Dipesan', 'pj-badge-info'],
            'terjual' => ['Terjual', 'pj-badge-muted'],
            'ditolak' => ['Ditolak', 'pj-badge-danger'],
        ];
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('user.dashboard') }}" class="pj-back">← Dashboard</a>
                <h1>Produk Saya</h1>
                <p>Kelola produk yang Anda jual di CampusTrade</p>
            </div>
            <a href="{{ route('penjual.produk.create') }}" class="pj-btn pj-btn-primary">+ Tambah Produk</a>
        </div>

        <div class="pj-table-wrap">
            <table class="pj-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produk as $item)
                        @php
                            [$label, $kelas] = $status[$item->status_produk] ?? [
                                $item->status_produk,
                                'pj-badge-muted',
                            ];
                            $bisaDiubah = in_array($item->status_produk, ['menunggu', 'tersedia', 'ditolak'], true);
                        @endphp
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
                                        <div class="pj-item-sub">{{ $item->kondisi }} · {{ $item->metode_pengiriman }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->harga_format }}</td>
                            <td><span class="pj-badge {{ $kelas }}">{{ $label }}</span></td>
                            <td>
                                @if ($bisaDiubah)
                                    <div class="pj-actions">
                                        <a href="{{ route('penjual.produk.edit', $item) }}"
                                            class="pj-btn pj-btn-ghost pj-btn-sm">Ubah</a>
                                        <form method="POST" action="{{ route('penjual.produk.destroy', $item) }}"
                                            onsubmit="return confirm('Hapus produk ini? Data dan fotonya akan dihapus permanen.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="pj-btn pj-btn-danger pj-btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="pj-empty">Belum ada produk. Mulai dengan menambahkan produk pertama
                                Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $produk->links('pagination::bootstrap-4') }}
    </div>
@endsection
