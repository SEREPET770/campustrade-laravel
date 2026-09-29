<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CampusTrade — Marketplace Barang Bekas Mahasiswa')</title>
    @vite(['resources/css/catalog.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>

    <header class="navbar">
        <a href="{{ route('catalog.index') }}" class="logo">
            <span class="icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M12 2l9 5v10l-9 5-9-5V7z" />
                    <path d="M3 7l9 5 9-5" />
                    <path d="M12 12v10" />
                </svg>
            </span>
            Campus<span class="logo-accent">Trade</span>
        </a>

        <nav class="nav-links">
            <a href="{{ route('catalog.index') }}"
                class="{{ request()->routeIs('catalog.index') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('catalog.index') }}#kategori-populer">Kategori</a>
            <a href="{{ route('produk.index') }}"
                class="{{ request()->routeIs('produk.index') ? 'active' : '' }}">Lokasi</a>
            <a href="{{ route('catalog.index') }}#tentang-kami">Tentang</a>
        </nav>

        <form action="{{ route('produk.index') }}" method="GET" class="nav-search-form">
            <input type="text" name="search" placeholder="Cari produk, kategori, atau kata kunci..."
                value="{{ request('search') }}">
            <button type="submit" class="nav-search-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
            </button>
        </form>

        <div class="nav-right">
            <a href="#" class="nav-cart" aria-label="Keranjang" title="Keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="9" cy="20" r="1.5" />
                    <circle cx="18" cy="20" r="1.5" />
                    <path d="M2 3h3l2.4 12.2a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 2-1.5L21 8H6" />
                </svg>
            </a>

            @auth
                <div class="nav-user">
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                        class="btn-outline">
                        Halo, {{ auth()->user()->nama }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-outline">Keluar</button>
                    </form>
                </div>
            @else
                <div class="nav-guest-actions">
                    <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                </div>
            @endauth
        </div>
    </header>

    @yield('hero')

    <main class="page-wrap">
        @yield('content')
    </main>

    <footer class="landing-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <span class="footer-logo" style="justify-content:center">CampusTrade</span>
                <p>Marketplace barang bekas mahasiswa.</p>
            </div>
            <div class="footer-menu">
                <a href="{{ route('catalog.index') }}">Beranda</a>
                <a href="{{ route('catalog.index') }}#produk-terbaru">Produk</a>
                <a href="{{ route('about') }}">Tentang</a>
            </div>
            <div class="footer-kontak">
                <h4 style="text-align: center">Kontak</h4>
                <p>Abid : 085792448847</p>
            </div>
        </div>
        <div class="footer-copyright">
            &copy; {{ date('Y') }} CampusTrade. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
