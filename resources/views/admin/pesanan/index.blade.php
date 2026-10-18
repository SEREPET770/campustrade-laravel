@extends('layouts.admin')
@section('title', 'Pesanan | Admin')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $tab = ['semua' => 'Semua'] + collect(\App\Models\Pesanan::STATUS)->map(fn($s) => $s[0])->all();
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="pj-back">← Dashboard</a>
                <h1>Pesanan</h1>
                <p>Pantau seluruh transaksi COD di CampusTrade</p>
            </div>
        </div>

        <div class="pj-tabs">
            @foreach ($tab as $key => $label)
                @php $total = $key === 'semua' ? $jumlah->sum() : ($jumlah[$key] ?? 0); @endphp
                <a href="{{ route('admin.pesanan.index', ['status' => $key]) }}"
                    class="pj-tab {{ $status === $key ? 'pj-tab-active' : '' }}">
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
                                <div class="pj-item-name">{{ $p->nama_produk }}</div>
                                <div class="pj-item-sub">{{ $p->kode }}</div>
                            </td>
                            <td>{{ $p->pembeli->nama ?? '-' }}</td>
                            <td>{{ $p->penjual->nama ?? '-' }}</td>
                            <td>{{ $p->total_format }}</td>
                            <td><span class="pj-badge {{ $p->kelas_status }}">{{ $p->label_status }}</span></td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('admin.pesanan.show', $p) }}"
                                    class="pj-btn pj-btn-ghost pj-btn-sm">Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="pj-empty">Belum ada pesanan pada status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pesanan->links('pagination::bootstrap-4') }}
    </div>
@endsection
