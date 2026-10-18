@extends('layouts.app')
@section('title', 'Pesanan ' . $pesanan->kode)

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
        $langkah = [
            'menunggu' => 'Menunggu konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'dikirim' => 'Dalam pengiriman',
            'selesai' => 'Selesai',
        ];
        $urut = array_keys($langkah);
        $posisi = array_search($pesanan->status, $urut, true);
        $selesai = $pesanan->status === 'selesai';
        $bisaBatal = in_array($pesanan->status, ['menunggu', 'dikonfirmasi'], true);
        $linkWa = in_array($pesanan->status, \App\Models\Pesanan::AKTIF, true)
            ? $pesanan->produk?->whatsapp_link
            : null;
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('pesanan.index') }}" class="pj-back">← Pesanan Saya</a>
                <h1>Pesanan {{ $pesanan->kode }}</h1>
                <p>
                    <span class="pj-badge {{ $pesanan->kelas_status }}">{{ $pesanan->label_status }}</span>
                    · dibuat {{ $pesanan->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        @if ($posisi !== false)
            <ol class="pj-steps">
                @foreach ($langkah as $kunci => $teks)
                    @php $i = array_search($kunci, $urut, true); @endphp
                    <li
                        class="pj-step {{ $i < $posisi || $selesai ? 'pj-step-done' : ($i === $posisi ? 'pj-step-now' : '') }}">
                        {{ $teks }}
                    </li>
                @endforeach
            </ol>
        @else
            <div class="pj-alert pj-alert-danger" style="margin-bottom:20px;">
                {{ $pesanan->status === 'ditolak' ? 'Pesanan ini ditolak oleh penjual.' : 'Pesanan ini dibatalkan.' }}
                @if ($pesanan->catatan_penjual)
                    <strong>Alasan:</strong> {{ $pesanan->catatan_penjual }}
                @endif
            </div>
        @endif

        <div class="pj-two">
            <div class="pj-card">
                <div class="pj-summary">
                    @if ($pesanan->produk?->foto_utama)
                        <img src="{{ $pesanan->produk->foto_utama }}" alt="" class="pj-thumb">
                    @else
                        <div class="pj-thumb pj-thumb-empty">Tanpa foto</div>
                    @endif
                    <div>
                        <div class="pj-item-name">{{ $pesanan->nama_produk }}</div>
                        <div class="pj-item-sub">Penjual: {{ $pesanan->penjual->nama ?? '-' }}</div>
                    </div>
                </div>

                <dl class="pj-rows" style="margin-top:18px;">
                    <dt>Harga</dt>
                    <dd>{{ $pesanan->harga_format }}</dd>
                    <dt>Pengiriman</dt>
                    <dd>{{ $pesanan->metode_pengiriman }}</dd>
                    <dt>Ongkir</dt>
                    <dd>{{ $pesanan->ongkir_format }}</dd>
                    <dt class="pj-total-dt">Total (bayar di tempat)</dt>
                    <dd class="pj-total-dd">{{ $pesanan->total_format }}</dd>
                </dl>
            </div>

            <div class="pj-card" style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <h2 class="pj-section-title">
                        {{ $pesanan->metode_pengiriman === 'Ambil di Tempat' ? 'Titik temu' : 'Alamat pengiriman' }}</h2>
                    <p class="pj-pre" style="margin:0; font-size:14px;">{{ $pesanan->alamat_temu }}</p>
                </div>

                @if ($pesanan->catatan_pembeli)
                    <div>
                        <h2 class="pj-section-title">Catatan Anda</h2>
                        <p class="pj-pre" style="margin:0; font-size:14px;">{{ $pesanan->catatan_pembeli }}</p>
                    </div>
                @endif

                @if ($linkWa)
                    <a href="{{ $linkWa }}" target="_blank" rel="noopener" class="pj-btn pj-btn-ghost">
                        Hubungi penjual via WhatsApp
                    </a>
                @endif

                @if ($bisaBatal)
                    <form method="POST" action="{{ route('pesanan.batalkan', $pesanan) }}"
                        onsubmit="return confirm('Batalkan pesanan ini? Produk akan kembali tersedia untuk pembeli lain.')">
                        @csrf @method('PATCH')
                        <button type="submit" class="pj-btn pj-btn-danger" style="width:100%; justify-content:center;">
                            Batalkan Pesanan
                        </button>
                    </form>
                @else
                    @if ($posisi !== false && !$selesai)
                        <p class="pj-note">Pesanan sedang dalam pengiriman dan tidak bisa dibatalkan oleh pembeli.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
