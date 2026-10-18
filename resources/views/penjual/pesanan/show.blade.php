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
        $aktif = in_array($pesanan->status, \App\Models\Pesanan::AKTIF, true);
        $linkWa = $aktif ? $pesanan->wa_pembeli : null;
    @endphp

    <div class="pj-wrap">
        <div class="pj-head">
            <div>
                <a href="{{ route('penjual.pesanan.index') }}" class="pj-back">← Pesanan Masuk</a>
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
                {{ $pesanan->status === 'ditolak' ? 'Anda menolak pesanan ini.' : 'Pesanan ini dibatalkan.' }}
                @if ($pesanan->catatan_penjual)
                    <strong>Alasan:</strong> {{ $pesanan->catatan_penjual }}
                @endif
            </div>
        @endif

        <div class="pj-two">
            <div class="pj-stack">
                <div class="pj-card">
                    <div class="pj-summary">
                        @if ($pesanan->produk?->foto_utama)
                            <img src="{{ $pesanan->produk->foto_utama }}" alt="" class="pj-thumb">
                        @else
                            <div class="pj-thumb pj-thumb-empty">Tanpa foto</div>
                        @endif
                        <div>
                            <div class="pj-item-name">{{ $pesanan->nama_produk }}</div>
                            <div class="pj-item-sub">{{ $pesanan->metode_pengiriman }}</div>
                        </div>
                    </div>

                    <dl class="pj-rows" style="margin-top:18px;">
                        <dt>Harga</dt>
                        <dd>{{ $pesanan->harga_format }}</dd>
                        <dt>Ongkir</dt>
                        <dd>{{ $pesanan->ongkir_format }}</dd>
                        <dt class="pj-total-dt">Total yang diterima (COD)</dt>
                        <dd class="pj-total-dd">{{ $pesanan->total_format }}</dd>
                    </dl>
                </div>

                <div class="pj-card" style="display:flex; flex-direction:column; gap:16px;">
                    <div>
                        <h2 class="pj-section-title">Pembeli</h2>
                        <dl class="pj-dl">
                            <dt>Nama</dt>
                            <dd>{{ $pesanan->pembeli->nama ?? '-' }}</dd>
                            <dt>NIM</dt>
                            <dd>{{ $pesanan->pembeli->nim ?? '-' }}</dd>
                        </dl>
                    </div>
                    <div>
                        <h2 class="pj-section-title">
                            {{ $pesanan->metode_pengiriman === 'Ambil di Tempat' ? 'Titik temu' : 'Alamat pengiriman' }}
                        </h2>
                        <p class="pj-pre" style="margin:0; font-size:14px;">{{ $pesanan->alamat_temu }}</p>
                    </div>
                    @if ($pesanan->catatan_pembeli)
                        <div>
                            <h2 class="pj-section-title">Catatan pembeli</h2>
                            <p class="pj-pre" style="margin:0; font-size:14px;">{{ $pesanan->catatan_pembeli }}</p>
                        </div>
                    @endif
                    @if ($linkWa)
                        <a href="{{ $linkWa }}" target="_blank" rel="noopener" class="pj-btn pj-btn-ghost">
                            Hubungi pembeli via WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            <div class="pj-stack">
                @if ($aktif)
                    <div class="pj-card pj-decision">
                        <h2 class="pj-section-title" style="margin:0;">Tindakan</h2>

                        @if ($pesanan->status === 'menunggu')
                            <form method="POST"
                                action="{{ route('penjual.pesanan.aksi', ['pesanan' => $pesanan, 'aksi' => 'konfirmasi']) }}"
                                onsubmit="return confirm('Konfirmasi pesanan ini?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="pj-btn pj-btn-success">✓ Konfirmasi pesanan</button>
                            </form>
                        @elseif ($pesanan->status === 'dikonfirmasi')
                            <form method="POST"
                                action="{{ route('penjual.pesanan.aksi', ['pesanan' => $pesanan, 'aksi' => 'kirim']) }}"
                                onsubmit="return confirm('Tandai pesanan ini sudah dikirim/diserahkan ke pembeli?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="pj-btn pj-btn-primary">Tandai dikirim / diserahkan</button>
                            </form>
                        @elseif ($pesanan->status === 'dikirim')
                            <form method="POST"
                                action="{{ route('penjual.pesanan.aksi', ['pesanan' => $pesanan, 'aksi' => 'selesai']) }}"
                                onsubmit="return confirm('Pastikan pembayaran tunai (COD) sudah Anda terima. Produk akan ditandai terjual. Lanjutkan?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="pj-btn pj-btn-success">✓ Tandai selesai (uang COD
                                    diterima)</button>
                            </form>
                        @endif

                        <form method="POST"
                            action="{{ route('penjual.pesanan.aksi', ['pesanan' => $pesanan, 'aksi' => $pesanan->status === 'menunggu' ? 'tolak' : 'batalkan']) }}"
                            onsubmit="return confirm('{{ $pesanan->status === 'menunggu' ? 'Tolak' : 'Batalkan' }} pesanan ini? Produk akan kembali tersedia.')">
                            @csrf @method('PATCH')
                            <label
                                for="catatan">{{ $pesanan->status === 'menunggu' ? 'Alasan menolak' : 'Alasan membatalkan' }}</label>
                            <textarea id="catatan" name="catatan" required maxlength="500">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <small class="pj-error">{{ $message }}</small>
                            @enderror
                            <button type="submit" class="pj-btn pj-btn-danger">
                                ✕ {{ $pesanan->status === 'menunggu' ? 'Tolak pesanan' : 'Batalkan pesanan' }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pj-card">
                        <p class="pj-note">Pesanan ini sudah berakhir, tidak ada tindakan yang tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
