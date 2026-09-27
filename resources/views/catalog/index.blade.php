@extends('layouts.site')

@section('title', 'CampusTrade — Marketplace Barang Bekas Mahasiswa')

@section('hero')
    <section class="hero-banner">
        <div class="hero-overlay-card">
            <span class="hero-tag">Marketplace Mahasiswa</span>
            <h1>Temukan Barang Bekas di Lingkungan <span class="hero-accent">Kampus</span></h1>
            <p>Jual beli barang bekas dengan aman, mudah, dan terjangkau. Bergabunglah dengan komunitas
                mahasiswa di CampusTrade!</p>

            <form action="{{ route('catalog.index') }}" method="GET" class="hero-search-form">
                <svg class="hero-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
                <input type="text" name="search" placeholder="Cari produk, kategori, atau kata kunci..."
                    value="{{ $search }}">
                <button type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M13 5l7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>
    </section>
@endsection

@section('content')
    <div class="content-layout">
        <div class="content-main">

            <section class="katalog-section" id="kategori-populer">
                <div class="katalog-header">
                    <h2 class="section-title">Kategori Populer</h2>
                </div>

                <div class="kategori-grid">
                    @forelse ($kategoriList as $kat)
                        <a href="{{ route('catalog.index', ['kategori' => $kat->id_kategori]) }}"
                            class="kategori-card {{ $kategoriAktif == $kat->id_kategori ? 'is-active' : '' }}">
                            <span class="kategori-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    {!! $kat->icon() !!}
                                </svg>
                            </span>
                            <span class="kategori-name">{{ $kat->nama_kategori }}</span>
                        </a>
                    @empty
                        <p class="kategori-empty">Belum ada kategori tersedia.</p>
                    @endforelse
                </div>
            </section>

            <section class="katalog-section">
                <div class="katalog-header">
                    <h2 class="section-title">Produk Terbaru</h2>
                </div>

                <div class="product-grid">
                    @forelse ($produkTerbaru as $item)
                        <a href="#" class="product-card">
                            <div class="card-image">
                                @if ($item->foto_utama)
                                    <img src="{{ $item->foto_utama }}" alt="{{ $item->nama_produk }}">
                                @else
                                    <div class="img-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="4" width="18" height="16" rx="2" />
                                            <circle cx="8.5" cy="9.5" r="1.5" />
                                            <path d="M21 16l-5-5-4 4-2-2-5 5" />
                                        </svg>
                                    </div>
                                @endif
                                <span class="badge-kondisi {{ $item->kondisi === 'Baru' ? 'badge-baru' : 'badge-bekas' }}">
                                    {{ $item->kondisi }}
                                </span>
                            </div>
                            <div class="card-content">
                                <h3 class="product-name">{{ $item->nama_produk }}</h3>
                                <p class="product-location">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 21s-7-6.5-7-11a7 7 0 0 1 14 0c0 4.5-7 11-7 11z" />
                                        <circle cx="12" cy="10" r="2.5" />
                                    </svg>
                                    {{ $item->lokasi?->nama_lokasi ?? 'Tidak diketahui' }}
                                </p>
                                <p class="product-price">{{ $item->harga_format }}</p>
                                <p class="product-seller">{{ $item->penjual->nama ?? '-' }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state">
                            <p>Belum ada produk yang tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="info-section" id="tentang-kami">
                <h2 class="section-title center">Tentang Kami</h2>
                <p class="tentang-text">
                    CampusTrade adalah marketplace barang bekas khusus mahasiswa. Kami mempertemukan
                    mahasiswa yang ingin menjual barang tidak terpakai dengan mahasiswa lain yang sedang
                    mencari barang berkualitas dengan harga terjangkau. Semua transaksi dilakukan langsung
                    antar sesama mahasiswa dalam satu platform yang aman, transparan, dan mudah digunakan.
                </p>
            </section>

            <section class="info-section">
                <h2 class="section-title center">Cara Kerja</h2>
                <div class="cara-kerja-grid">
                    <div class="cara-kerja-step">
                        <span class="cara-kerja-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="8" r="3.5" />
                                <path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                                <path d="M19 8h4M21 6v4" />
                            </svg>
                        </span>
                        <h3>1. Daftar Akun</h3>
                        <p>Buat akun menggunakan data mahasiswa kamu, gratis dan cepat.</p>
                    </div>
                    <div class="cara-kerja-step">
                        <span class="cara-kerja-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 16V4" />
                                <path d="M7 9l5-5 5 5" />
                                <path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3" />
                            </svg>
                        </span>
                        <h3>2. Upload Produk</h3>
                        <p>Unggah foto dan detail barang bekas yang ingin kamu jual.</p>
                    </div>
                    <div class="cara-kerja-step">
                        <span class="cara-kerja-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 5h16v10H8l-4 4V5z" />
                            </svg>
                        </span>
                        <h3>3. Temukan Pembeli</h3>
                        <p>Pembeli akan menghubungi kamu langsung melalui chat.</p>
                    </div>
                    <div class="cara-kerja-step">
                        <span class="cara-kerja-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M8 12.5l2.5 2.5L16 9.5" />
                            </svg>
                        </span>
                        <h3>4. Selesaikan Transaksi</h3>
                        <p>Sepakati harga dan selesaikan transaksi dengan aman.</p>
                    </div>
                </div>
            </section>

            <section class="cta-section">
                <h2>Mulai Jual dan Temukan Barang Bekas Berkualitas Hari Ini</h2>
                <div class="cta-buttons">
                    @auth
                        <a href="{{ $ctaJualLink }}" class="btn-primary">{{ $ctaJualText }}</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                        <a href="{{ route('login') }}" class="btn-outline-light">Masuk</a>
                    @endauth
                </div>
            </section>

        </div>

        <aside class="content-sidebar">
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17l-6.1 3.5 1.5-6.8L2.2 9l6.9-.7z" />
                    </svg>
                    Produk Pilihan
                </h3>
                <ul class="sidebar-product-list">
                    @forelse ($produkPilihan as $item)
                        <li>
                            <div class="sidebar-thumb">
                                @if ($item->foto_utama)
                                    <img src="{{ $item->foto_utama }}" alt="{{ $item->nama_produk }}">
                                @else
                                    <div class="img-placeholder small">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5">
                                            <rect x="3" y="4" width="18" height="16" rx="2" />
                                            <circle cx="8.5" cy="9.5" r="1.5" />
                                            <path d="M21 16l-5-5-4 4-2-2-5 5" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="sidebar-product-name">{{ $item->nama_produk }}</p>
                                <p class="sidebar-product-price">{{ $item->harga_format }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="sidebar-empty">Belum ada produk pilihan.</li>
                    @endforelse
                </ul>
            </div>

            @guest
                <div class="sidebar-cta">
                    <span class="sidebar-cta-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3l10 5-10 5L2 8z" />
                            <path d="M6 10.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5" />
                            <path d="M22 8v6" />
                        </svg>
                    </span>
                    <h3>Gabung sekarang!</h3>
                    <p>Buat akun untuk menikmati fitur lengkap CampusTrade.</p>
                    <a href="{{ route('register') }}" class="btn-white">
                        Daftar Gratis
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M13 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endguest
        </aside>
    </div>
@endsection
