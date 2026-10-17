@extends('layouts.app')
@section('title', 'Periksa Produk | CampusTrade Admin')

@push('styles')
    @vite('resources/css/penjual.css')
@endpush

@section('content')
    @php
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
                <a href="{{ route('admin.produk.index') }}" class="pj-back">← Verifikasi Produk</a>
                <h1>{{ $produk->nama_produk }}</h1>
                <p>
                    <span
                        class="pj-badge {{ $badge[$produk->status_produk] ?? 'pj-badge-muted' }}">{{ ucfirst($produk->status_produk) }}</span>
                    · diajukan {{ $produk->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <div class="pj-detail">
            <div class="pj-stack">
                <div class="pj-card">
                    <h2 class="pj-section-title">Foto produk</h2>
                    @if ($produk->gambar->isNotEmpty())
                        <div class="pj-detail-photos">
                            @foreach ($produk->gambar as $g)
                                <a href="{{ asset('storage/produk/' . $g->image_path) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/produk/' . $g->image_path) }}" alt="">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p style="color:#6b7280; margin:0;">Produk ini belum memiliki foto.</p>
                    @endif
                </div>

                <div class="pj-card">
                    <h2 class="pj-section-title">Detail produk</h2>
                    <dl class="pj-dl">
                        <dt>Kategori</dt>
                        <dd>{{ $produk->kategori->nama_kategori ?? '-' }}</dd>
                        <dt>Lokasi</dt>
                        <dd>{{ $produk->lokasi->nama_lokasi ?? '-' }}</dd>
                        <dt>Harga</dt>
                        <dd>{{ $produk->harga_format }}</dd>
                        <dt>Kondisi</dt>
                        <dd>{{ $produk->kondisi }}</dd>
                        <dt>Merek</dt>
                        <dd>{{ $produk->brand ?: '-' }}</dd>
                        <dt>Penulis/penerbit</dt>
                        <dd>{{ $produk->penulis_penerbit ?: '-' }}</dd>
                        <dt>Pengiriman</dt>
                        <dd>{{ $produk->metode_pengiriman }}
                            @if ((float) $produk->ongkir > 0)
                                (ongkir Rp {{ number_format((float) $produk->ongkir, 0, ',', '.') }})
                            @endif
                        </dd>
                        <dt>Deskripsi</dt>
                        <dd>{{ $produk->deskripsi }}</dd>
                        <dt>Alasan dijual</dt>
                        <dd>{{ $produk->alasan_jual ?: '-' }}</dd>
                        <dt>Kelebihan</dt>
                        <dd>{{ $produk->kelebihan ?: '-' }}</dd>
                        <dt>Kekurangan</dt>
                        <dd>{{ $produk->kekurangan ?: '-' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="pj-stack">
                <div class="pj-card">
                    <h2 class="pj-section-title">Penjual</h2>
                    <dl class="pj-dl">
                        <dt>Nama</dt>
                        <dd>{{ $produk->penjual->nama ?? '-' }}</dd>
                        <dt>NIM</dt>
                        <dd>{{ $produk->penjual->nim ?? '-' }}</dd>
                        <dt>WhatsApp</dt>
                        <dd>{{ $produk->penjual->no_whatsapp ?? '-' }}</dd>
                        <dt>Status akun</dt>
                        <dd>{{ ucfirst($produk->penjual->status_verifikasi ?? '-') }}</dd>
                    </dl>
                </div>

                @if ($produk->status_produk === 'menunggu')
                    <div class="pj-card pj-decision">
                        <h2 class="pj-section-title" style="margin:0;">Keputusan verifikasi</h2>

                        <form method="POST" action="{{ route('admin.produk.approve', $produk) }}"
                            onsubmit="return confirm('Setujui produk ini? Produk akan langsung tampil di katalog.')">
                            @csrf @method('PATCH')
                            <button type="submit" class="pj-btn pj-btn-success">✓ Setujui &amp; tayangkan</button>
                        </form>

                        <form method="POST" action="{{ route('admin.produk.reject', $produk) }}"
                            onsubmit="return confirm('Tolak produk ini?')">
                            @csrf @method('PATCH')
                            <label for="catatan">Alasan penolakan</label>
                            <textarea id="catatan" name="catatan" required maxlength="500"
                                placeholder="Contoh: foto kurang jelas, harga tidak sesuai kondisi barang.">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <small class="pj-error">{{ $message }}</small>
                            @enderror
                            <button type="submit" class="pj-btn pj-btn-danger">✕ Tolak produk</button>
                        </form>
                    </div>
                @endif

                <div class="pj-card">
                    <h2 class="pj-section-title">Riwayat verifikasi</h2>
                    <ul class="pj-log">
                        @forelse ($riwayat as $r)
                            <li>
                                <span
                                    class="pj-badge {{ $r->status === 'terverifikasi' ? 'pj-badge-success' : 'pj-badge-danger' }}">
                                    {{ $r->status === 'terverifikasi' ? 'Disetujui' : 'Ditolak' }}
                                </span>
                                @if ($r->catatan)
                                    <div style="margin-top:6px;">{{ $r->catatan }}</div>
                                @endif
                                <small>{{ $r->created_at?->format('d/m/Y H:i') }} · oleh
                                    {{ $r->admin->nama ?? 'Admin' }}</small>
                            </li>
                        @empty
                            <li style="color:#6b7280;">Belum ada riwayat verifikasi.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
