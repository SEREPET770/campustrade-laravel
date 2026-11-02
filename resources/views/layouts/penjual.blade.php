<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard | CampusTrade')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/admin.css'])
    @stack('styles')
</head>

<body class="admin-body seller-body">
    @php
        $user = auth()->user();
        $pesananMasuk = \App\Models\Pesanan::where('id_penjual', $user->getKey())->where('status', 'menunggu')->count();
    @endphp

    <aside class="admin-sidebar">
        <a href="{{ route('user.dashboard') }}" class="side-logo">
            <span class="logo-campus">campus</span><span class="logo-trade">trade</span>
        </a>

        <div class="side-profile">
            <div class="avatar-circle">{{ mb_strtoupper(mb_substr($user->nama, 0, 1)) }}</div>
            <div>
                <p>{{ $user->nama }}</p>
                <small>Penjual</small>
            </div>
        </div>

        <nav class="side-nav">
            <a href="{{ route('user.dashboard') }}"
                class="side-link {{ request()->routeIs('user.dashboard') ? 'is-active' : '' }}">
                <i class="fa-solid fa-house"></i><span class="lbl">Dashboard</span>
            </a>
            <a href="{{ route('penjual.produk.index') }}"
                class="side-link {{ request()->routeIs('penjual.produk.*') ? 'is-active' : '' }}">
                <i class="fa-solid fa-box"></i><span class="lbl">Produk Saya</span>
            </a>
            <a href="{{ route('penjual.pesanan.index') }}"
                class="side-link {{ request()->routeIs('penjual.pesanan.*') ? 'is-active' : '' }}">
                <i class="fa-solid fa-inbox"></i><span class="lbl">Pesanan Masuk</span>
                @if ($pesananMasuk > 0)
                    <span class="count lbl">{{ $pesananMasuk }}</span>
                @endif
            </a>
            <a href="{{ route('pesanan.index') }}"
                class="side-link {{ request()->routeIs('pesanan.*') ? 'is-active' : '' }}">
                <i class="fa-solid fa-bag-shopping"></i><span class="lbl">Pesanan Saya</span>
            </a>
            <a href="{{ route('produk.index') }}" class="side-link">
                <i class="fa-solid fa-store"></i><span class="lbl">Lihat Katalog</span>
            </a>
        </nav>

        <div class="side-promo">
            <i class="fa-solid fa-store"></i>
            <strong>Kelola tokomu lebih mudah!</strong>
            Pantau pesanan, kelola produk, dan tingkatkan penjualan di CampusTrade.
        </div>

        <form method="POST" action="{{ route('logout') }}" class="side-logout">
            @csrf
            <button type="submit" class="side-link">
                <i class="fa-solid fa-right-from-bracket"></i><span class="lbl">Keluar</span>
            </button>
        </form>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <form method="GET" action="{{ route('penjual.produk.index') }}" class="top-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk saya..."
                    aria-label="Cari produk saya">
            </form>
            <div class="topbar-right">
                <a href="{{ route('penjual.pesanan.index', ['status' => 'menunggu']) }}" class="bell"
                    aria-label="Pesanan menunggu konfirmasi">
                    <i class="fa-regular fa-bell"></i>
                    @if ($pesananMasuk > 0)
                        <span class="bell-count">{{ $pesananMasuk }}</span>
                    @endif
                </a>
                <div class="topbar-profile">
                    <div class="avatar-circle">{{ mb_strtoupper(mb_substr($user->nama, 0, 1)) }}</div>
                    <div>
                        <p class="profile-name">{{ $user->nama }}</p>
                        <p class="profile-role">Penjual</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <div id="popupContainer"></div>
    <script>
        function showPopup(message, type = 'success') {
            const icons = {
                success: '✅',
                error: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };
            const container = document.getElementById('popupContainer');
            const item = document.createElement('div');
            item.className = `popup-item popup-${type}`;

            const icon = document.createElement('span');
            icon.textContent = icons[type] ?? '';
            const text = document.createElement('span');
            text.textContent = message;

            item.append(icon, text);
            container.appendChild(item);
            setTimeout(() => item.remove(), 3500);
        }
        @if (session('notif'))
            showPopup(@json(session('notif')['pesan']), @json(session('notif')['tipe']));
        @endif
    </script>
    @stack('scripts')
</body>

</html>
