@extends('layouts.site')

@section('title', 'Tentang Kami — CampusTrade')

@section('content')
    <div class="about-page">

        {{-- HERO --}}
        <section class="about-hero">
            <div class="about-hero-text">
                <span class="about-tag">Tentang Kami</span>
                <h1>Mengenal Lebih Dekat Campus<span class="hero-accent">Trade</span></h1>
                <p>CampusTrade adalah platform jual beli barang bekas yang dikhususkan untuk mahasiswa. Kami hadir
                    untuk menciptakan lingkungan kampus yang lebih hemat, ramah lingkungan, dan saling membantu.</p>
                <div class="about-slogan">Barang Berkualitas, Kampus Lebih Berdaya</div>
            </div>

            <div class="about-hero-art">
                <svg viewBox="0 0 460 280" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <ellipse cx="230" cy="150" rx="190" ry="120" fill="#ffffff"
                        fill-opacity="0.55" />
                    <rect x="352" y="70" width="70" height="96" rx="24" fill="#1f508a" />
                    <rect x="362" y="118" width="50" height="34" rx="8" fill="#163b66" />
                    <rect x="120" y="50" width="220" height="140" rx="10" fill="#163b66" />
                    <rect x="128" y="58" width="204" height="124" rx="6" fill="#ffffff" />
                    <path d="M230 84l22 12v24l-22 12-22-12V96z" stroke="#1d6fa5" stroke-width="5" stroke-linejoin="round" />
                    <path d="M208 96l22 12 22-12" stroke="#1d6fa5" stroke-width="5" stroke-linejoin="round" />
                    <text x="230" y="162" text-anchor="middle" font-family="Arial, sans-serif" font-size="16"
                        font-weight="700" fill="#163b66">Campus<tspan fill="#1d6fa5">Trade</tspan></text>
                    <path d="M96 196h268l-14 22H110z" fill="#c9d9e8" />
                    <rect x="110" y="190" width="240" height="8" rx="4" fill="#9fb8d1" />
                    <rect x="338" y="196" width="90" height="16" rx="3" fill="#1f508a" />
                    <rect x="330" y="212" width="98" height="16" rx="3" fill="#1d6fa5" />
                    <rect x="342" y="228" width="86" height="16" rx="3" fill="#163b66" />
                    <rect x="150" y="230" width="60" height="30" rx="5" fill="#163b66"
                        transform="rotate(-8 180 245)" />
                    <rect x="52" y="190" width="40" height="56" rx="6" fill="#ffffff" />
                    <path d="M72 190c-22-10-30-40-20-60 14 12 22 34 20 60z" fill="#007c85" />
                    <path d="M72 190c8-24 26-40 44-42-4 22-20 38-44 42z" fill="#00636a" />
                </svg>
                <div class="about-script">Dari Mahasiswa<br>Untuk Mahasiswa</div>
            </div>
        </section>

        {{-- MISI / VISI / NILAI / DAMPAK --}}
        <section class="about-pillars">
            <article class="about-pillar">
                <span class="about-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <circle cx="12" cy="12" r="5" />
                        <circle cx="12" cy="12" r="1.5" />
                        <path d="M12 12l7-7" />
                    </svg>
                </span>
                <div>
                    <h3>Misi Kami</h3>
                    <p>Memfasilitasi mahasiswa dalam jual beli barang bekas yang aman, mudah, dan terpercaya.</p>
                </div>
            </article>

            <article class="about-pillar">
                <span class="about-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </span>
                <div>
                    <h3>Visi Kami</h3>
                    <p>Menjadi platform marketplace kampus terbaik yang mendukung gaya hidup berkelanjutan dan
                        kebersamaan mahasiswa.</p>
                </div>
            </article>

            <article class="about-pillar">
                <span class="about-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3h12l4 6-10 12L2 9z" />
                        <path d="M2 9h20" />
                        <path d="M9 3l3 6 3-6" />
                    </svg>
                </span>
                <div>
                    <h3>Nilai Kami</h3>
                    <ul class="about-checklist">
                        @foreach (['Kepercayaan', 'Kemudahan', 'Kebersamaan', 'Keberlanjutan'] as $nilai)
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12.5l4.5 4.5L19 7.5" />
                                </svg>
                                {{ $nilai }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </article>

            <article class="about-pillar">
                <span class="about-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 4C10 4 4 9 4 15a5 5 0 0 0 5 5c6 0 11-6 11-16z" />
                        <path d="M4 20c3-6 7-9 12-11" />
                    </svg>
                </span>
                <div>
                    <h3>Dampak Positif</h3>
                    <p>Kami percaya, dengan menggunakan kembali barang yang masih layak, kita bisa mengurangi limbah
                        dan membuat kampus lebih hijau.</p>
                </div>
            </article>
        </section>

        {{-- CERITA + MENGAPA MEMILIH --}}
        <section class="about-split">
            <div class="about-story">
                <span class="about-tag">Tentang CampusTrade</span>
                <h2>Solusi Jual Beli Barang Bekas di Lingkungan <span class="hero-accent">Kampus</span></h2>
                <p>CampusTrade lahir dari kepedulian terhadap kebutuhan mahasiswa akan barang yang terjangkau,
                    sekaligus keinginan untuk mengurangi limbah dan mendukung gaya hidup berkelanjutan. Kami
                    menyediakan platform yang mudah digunakan, aman, dan terpercaya untuk bertransaksi antar
                    mahasiswa di lingkungan kampus.</p>

                <div class="about-note">
                    <span class="about-icon about-icon-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="8" r="3.5" />
                            <path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                            <circle cx="17" cy="9" r="2.5" />
                            <path d="M17 14c3 0 5 2 5 5" />
                        </svg>
                    </span>
                    <span>Bersama CampusTrade, mari ciptakan kampus yang lebih hemat, lebih hijau, dan lebih saling
                        mendukung.</span>
                </div>
            </div>

            <div class="about-why">
                <div class="about-why-main">
                    <h3>Mengapa Memilih CampusTrade?</h3>
                    <div class="about-why-grid">
                        <div class="about-why-item">
                            <span class="about-why-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z" />
                                    <path d="M8.5 12l2.5 2.5 4.5-5" />
                                </svg>
                            </span>
                            <div>
                                <strong>Aman &amp; Terpercaya</strong>
                                <p>Verifikasi pengguna oleh admin</p>
                            </div>
                        </div>
                        <div class="about-why-item">
                            <span class="about-why-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21s-7-6.5-7-11a7 7 0 0 1 14 0c0 4.5-7 11-7 11z" />
                                    <circle cx="12" cy="10" r="2.5" />
                                </svg>
                            </span>
                            <div>
                                <strong>Lokasi Kampus</strong>
                                <p>Transaksi lebih mudah</p>
                            </div>
                        </div>
                        <div class="about-why-item">
                            <span class="about-why-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 4C10 4 4 9 4 15a5 5 0 0 0 5 5c6 0 11-6 11-16z" />
                                    <path d="M4 20c3-6 7-9 12-11" />
                                </svg>
                            </span>
                            <div>
                                <strong>Ramah Lingkungan</strong>
                                <p>Gunakan kembali, kurangi limbah</p>
                            </div>
                        </div>
                        <div class="about-why-item">
                            <span class="about-why-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="8" r="3.5" />
                                    <path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                                    <circle cx="17" cy="9" r="2.5" />
                                    <path d="M17 14c3 0 5 2 5 5" />
                                </svg>
                            </span>
                            <div>
                                <strong>Komunitas Mahasiswa</strong>
                                <p>Saling membantu, saling menguntungkan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-why-art">
                    <div class="about-script">Jual Beli Mudah,<br>Kampus Lebih Baik</div>
                    <svg viewBox="0 0 260 150" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="40" cy="110" r="22" fill="#9fc3e0" />
                        <circle cx="24" cy="124" r="16" fill="#c4dbee" />
                        <circle cx="220" cy="108" r="24" fill="#9fc3e0" />
                        <circle cx="240" cy="124" r="16" fill="#c4dbee" />
                        <rect x="80" y="60" width="100" height="78" fill="#ffffff" stroke="#1d6fa5"
                            stroke-width="3" />
                        <path d="M70 62L130 30l60 32z" fill="#1d6fa5" />
                        <path d="M130 30V10" stroke="#1d6fa5" stroke-width="3" />
                        <path d="M130 10h16l-4 6 4 6h-16z" fill="#1f508a" />
                        <circle cx="130" cy="72" r="6" fill="#dbe9f5" stroke="#1d6fa5" stroke-width="2" />
                        <g fill="#dbe9f5" stroke="#1d6fa5" stroke-width="2">
                            <rect x="92" y="84" width="16" height="16" />
                            <rect x="152" y="84" width="16" height="16" />
                            <rect x="92" y="108" width="16" height="16" />
                            <rect x="152" y="108" width="16" height="16" />
                        </g>
                        <rect x="120" y="106" width="20" height="32" fill="#1f508a" />
                        <rect x="0" y="136" width="260" height="8" rx="4" fill="#c9d9e8" />
                    </svg>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="about-cta">
            <span class="about-cta-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M12 3l10 5-10 5L2 8z" />
                    <path d="M6 10.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5" />
                    <path d="M22 8v6" />
                </svg>
            </span>
            <div class="about-cta-text">
                <h2>Bergabunglah dengan <span>CampusTrade</span></h2>
                <p>Jadilah bagian dari komunitas mahasiswa yang peduli dan saling mendukung.</p>
            </div>
            @guest
                <a href="{{ route('register') }}" class="btn-white">
                    Daftar Sekarang
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M13 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <a href="{{ route('catalog.index') }}" class="btn-white">
                    Jelajahi Katalog
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M13 5l7 7-7 7" />
                    </svg>
                </a>
            @endguest
        </section>

    </div>
@endsection
