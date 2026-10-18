@extends('layouts.site')

@section('title', $produk->nama_produk . ' — CampusTrade')

@push('styles')
    @vite(['resources/css/detail.css'])
@endpush

@section('content')
    @php
        $fotos = $produk->foto_urls;
        $penjual = $produk->penjual;
        $spesifikasi = array_filter(
            [
                ['fa-solid fa-tag', 'Kondisi', $produk->kondisi],
                ['fa-solid fa-copyright', 'Merek', $produk->brand],
                ['fa-solid fa-book', 'Penulis/Penerbit', $produk->penulis_penerbit],
                ['fa-solid fa-layer-group', 'Kategori', $produk->kategori?->nama_kategori],
                ['fa-solid fa-truck', 'Pengiriman', $produk->metode_pengiriman],
                ['fa-solid fa-money-bill-wave', 'Ongkir', $produk->ongkir_format],
            ],
            fn($s) => filled($s[2]),
        );
    @endphp

    <div class="detail-page">
        {{-- Breadcrumb --}}
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('catalog.index') }}">Beranda</a>
            <span class="crumb-sep">›</span>
            <a href="{{ route('produk.index') }}">Produk</a>
            @if ($produk->kategori)
                <span class="crumb-sep">›</span>
                <a href="{{ route('produk.index', ['kategori' => [$produk->id_kategori]]) }}">
                    {{ $produk->kategori->nama_kategori }}
                </a>
            @endif
            <span class="crumb-sep">›</span>
            <span class="crumb-current">{{ $produk->nama_produk }}</span>
        </nav>

        <div class="detail-layout">
            <div class="detail-main">
                <section class="detail-hero">
                    {{-- Galeri --}}
                    <div class="detail-card detail-gallery">
                        <div class="gallery-main">
                            <span class="badge-kondisi {{ $produk->kondisi === 'Baru' ? 'badge-baru' : 'badge-bekas' }}">
                                {{ $produk->kondisi }}
                            </span>

                            @if ($fotos)
                                <img id="gallery-main-img" src="{{ $fotos[0] }}" alt="{{ $produk->nama_produk }}">
                                @if (count($fotos) > 1)
                                    <button type="button" class="gallery-nav prev" data-dir="-1"
                                        aria-label="Foto sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
                                    <button type="button" class="gallery-nav next" data-dir="1"
                                        aria-label="Foto berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
                                @endif
                            @else
                                <div class="img-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <circle cx="8.5" cy="9.5" r="1.5" />
                                        <path d="M21 16l-5-5-4 4-2-2-5 5" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        @if (count($fotos) > 1)
                            <div class="gallery-thumbs">
                                @foreach ($fotos as $foto)
                                    <button type="button" class="thumb {{ $loop->first ? 'is-active' : '' }}"
                                        aria-label="Foto {{ $loop->iteration }}">
                                        <img src="{{ $foto }}" alt="">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Info produk --}}
                    <div class="detail-card detail-info">
                        @if ($produk->kategori)
                            <a href="{{ route('produk.index', ['kategori' => [$produk->id_kategori]]) }}"
                                class="chip-kategori">{{ $produk->kategori->nama_kategori }}</a>
                        @endif

                        <h1 class="detail-title">{{ $produk->nama_produk }}</h1>
                        <p class="detail-price">{{ $produk->harga_format }}</p>

                        <div class="detail-meta">
                            <span class="meta-chip"><i class="fa-solid fa-tag"></i>{{ $produk->kondisi }}</span>
                            <span class="meta-chip"><i
                                    class="fa-solid fa-location-dot"></i>{{ $produk->lokasi->nama_lokasi ?? 'Tidak diketahui' }}</span>
                            <span class="meta-chip"><i class="fa-solid fa-user"></i>{{ $penjual->nama ?? '-' }}</span>
                        </div>

                        <div class="detail-block">
                            <h2 class="block-title">Deskripsi Produk</h2>
                            <p class="detail-desc">{!! nl2br(e($produk->deskripsi)) !!}</p>
                        </div>

                        @if ($spesifikasi)
                            <div class="detail-block">
                                <h2 class="block-title">Spesifikasi</h2>
                                <ul class="spec-list">
                                    @foreach ($spesifikasi as [$ikon, $label, $nilai])
                                        <li>
                                            <i class="{{ $ikon }}"></i>
                                            <span class="spec-label">{{ $label }}</span>
                                            <span class="spec-value">: {{ $nilai }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Tab --}}
                <section class="detail-card detail-tabs">
                    <div class="tab-nav" role="tablist">
                        <button type="button" class="tab-btn is-active" data-tab="informasi">Informasi</button>
                        <button type="button" class="tab-btn" data-tab="penjual">
                            Produk Lain dari Penjual ({{ $produkPenjual->count() }})
                        </button>
                    </div>

                    <div class="tab-panel is-active" id="tab-informasi">
                        <h2 class="block-title">Kelebihan &amp; Kekurangan</h2>
                        <div class="pros-cons">
                            <div class="pros-box">
                                <h3><i class="fa-solid fa-circle-check"></i>Kelebihan</h3>
                                @if ($produk->kelebihan_list)
                                    <ul>
                                        @foreach ($produk->kelebihan_list as $poin)
                                            <li>{{ $poin }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="muted">Penjual belum mencantumkan kelebihan.</p>
                                @endif
                            </div>
                            <div class="cons-box">
                                <h3><i class="fa-solid fa-circle-minus"></i>Kekurangan</h3>
                                @if ($produk->kekurangan_list)
                                    <ul>
                                        @foreach ($produk->kekurangan_list as $poin)
                                            <li>{{ $poin }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="muted">Penjual belum mencantumkan kekurangan.</p>
                                @endif
                            </div>
                        </div>

                        @if ($produk->alasan_jual)
                            <h2 class="block-title reason-title">Alasan Dijual</h2>
                            <p class="detail-desc">{{ $produk->alasan_jual }}</p>
                        @endif
                    </div>

                    <div class="tab-panel" id="tab-penjual">
                        @if ($produkPenjual->isNotEmpty())
                            <div class="similar-grid seller-grid">
                                @foreach ($produkPenjual as $item)
                                    @include('produk.partials.kartu', ['item' => $item])
                                @endforeach
                            </div>
                        @else
                            <p class="muted">Penjual ini belum punya produk lain yang tersedia.</p>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Kartu penjual --}}
            <aside class="detail-aside">
                <div class="detail-card seller-card">
                    <div class="seller-head">
                        <div class="seller-avatar">{{ mb_strtoupper(mb_substr($penjual->nama ?? '?', 0, 1)) }}</div>
                        <div>
                            <p class="seller-name">{{ $penjual->nama ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="seller-stats">
                        <div><strong>{{ $jumlahAktif }}</strong><span>Produk Aktif</span></div>
                        <div><strong>{{ $jumlahTerjual }}</strong><span>Terjual</span></div>
                    </div>

                    <div class="seller-location">
                        <p class="loc-title">Lokasi Produk</p>
                        <p class="loc-name">
                            <i class="fa-solid fa-location-dot"></i>
                            {{ $produk->lokasi->nama_lokasi ?? 'Tidak diketahui' }}
                        </p>
                        @if ($produk->latitude && $produk->longitude)
                            <a href="https://www.google.com/maps?q={{ $produk->latitude }},{{ $produk->longitude }}"
                                target="_blank" rel="noopener" class="loc-map">
                                Lihat peta <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    @if ($produk->whatsapp_link)
                        <div class="wa-box">
                            <p class="wa-title">Chat Penjual</p>
                            <p class="wa-sub">Hubungi penjual melalui WhatsApp</p>
                            <a href="{{ $produk->whatsapp_link }}" target="_blank" rel="noopener" class="btn-wa">
                                <i class="fa-brands fa-whatsapp"></i>Chat via WhatsApp
                            </a>
                        </div>
                    @endif

                    <div class="action-card">
                        @auth
                            @if ((int) auth()->id() === (int) $produk->id_user)
                                <a href="{{ route('penjual.produk.edit', $produk) }}" class="btn-buy">
                                    <i class="fa-solid fa-pen"></i>Ini produk Anda · Ubah
                                </a>
                            @else
                                <a href="{{ route('pesanan.create', $produk) }}" class="btn-buy">
                                    <i class="fa-solid fa-bag-shopping"></i>Beli Sekarang (COD)
                                </a>
                                <div class="action-row">
                                    <button type="button" class="btn-cart" data-aksi="Keranjang">
                                        <i class="fa-solid fa-cart-plus"></i>Masukkan Keranjang
                                    </button>
                                    <button type="button" class="btn-save" data-aksi="Simpan produk"
                                        aria-label="Simpan produk">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-buy">
                                <i class="fa-solid fa-bag-shopping"></i>Beli Sekarang
                            </a>
                            <div class="action-row">
                                <a href="{{ route('login') }}" class="btn-cart">
                                    <i class="fa-solid fa-cart-plus"></i>Masukkan Keranjang
                                </a>
                                <a href="{{ route('login') }}" class="btn-save" aria-label="Simpan produk">
                                    <i class="fa-regular fa-heart"></i>
                                </a>
                            </div>
                        @endauth
                    </div>
                    <div class="toast" id="toast" role="status"></div>
                </div>
            </aside>
        </div>

        {{-- Produk serupa --}}
        @if ($produkSerupa->isNotEmpty())
            <section class="detail-card similar-section">
                <div class="similar-head">
                    <h2 class="block-title">Produk Serupa</h2>
                    <a href="{{ route('produk.index', ['kategori' => [$produk->id_kategori]]) }}" class="see-all">
                        Lihat Semua <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="similar-grid">
                    @foreach ($produkSerupa as $item)
                        @include('produk.partials.kartu', ['item' => $item])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const fotos = @json($fotos);
            const img = document.getElementById('gallery-main-img');
            const thumbs = document.querySelectorAll('.thumb');
            let idx = 0;

            const tampil = (i) => {
                if (!img || !fotos.length) return;
                idx = (i + fotos.length) % fotos.length;
                img.src = fotos[idx];
                thumbs.forEach((t, n) => t.classList.toggle('is-active', n === idx));
            };

            document.querySelectorAll('.gallery-nav').forEach(b =>
                b.addEventListener('click', () => tampil(idx + Number(b.dataset.dir))));
            thumbs.forEach((t, n) => t.addEventListener('click', () => tampil(n)));

            const tabBtns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');
            tabBtns.forEach(btn => btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.toggle('is-active', b === btn));
                panels.forEach(p => p.classList.toggle('is-active', p.id === 'tab-' + btn.dataset.tab));
            }));
            const toast = document.getElementById('toast');
            document.querySelectorAll('[data-aksi]').forEach(b => b.addEventListener('click', () => {
                toast.textContent = b.dataset.aksi + ' belum tersedia, fiturnya masih dalam pengerjaan.';
                toast.classList.add('is-show');
                setTimeout(() => toast.classList.remove('is-show'), 2500);
            }));
        })();
    </script>
@endpush
