@extends('layouts.app')
@section('title', 'Pesanan Masuk')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $tab = [
            'semua' => ['Semua', null],
            'menunggu' => ['Perlu konfirmasi', ['menunggu']],
            'berjalan' => ['Berjalan', ['dikonfirmasi', 'dikirim']],
            'selesai' => ['Selesai', ['selesai']],
            'batal' => ['Ditolak / Dibatalkan', ['ditolak', 'dibatalkan']],
        ];
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('user.dashboard') }}" class="pj-back">← Dashboard</a>
                <h1>Pesanan Masuk</h1>
                <p>Kelola pesanan COD dari pembeli</p>
            </div>
        </div>

        <div class="pj-tabs">
            @foreach ($tab as $key => [$label, $daftarStatus])
                @php
                    $total =
                        $daftarStatus === null
                            ? $jumlah->sum()
                            : collect($daftarStatus)->sum(fn($s) => $jumlah[$s] ?? 0);
                @endphp
                <a href="{{ route('penjual.pesanan.index', ['status' => $key]) }}"
                    class="pj-tab {{ $filter === $key ? 'pj-tab-active' : '' }}">
                    {{ $label }} <span class="pj-count">{{ $total }}</span>
                </a>
            @endforeach
        </div>

        <div class="pj-table-wrap">
            <table class="pj-table">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        <th>Pembeli</th>
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
                            <td>{{ $p->pembeli->nama ?? '-' }}</td>
                            <td>{{ $p->total_format }}</td>
                            <td><span class="pj-badge {{ $p->kelas_status }}">{{ $p->label_status }}</span></td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('penjual.pesanan.show', $p) }}"
                                    class="pj-btn {{ $p->status === 'menunggu' ? 'pj-btn-primary' : 'pj-btn-ghost' }} pj-btn-sm">
                                    {{ $p->status === 'menunggu' ? 'Tanggapi' : 'Kelola' }}
                                </a>
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
