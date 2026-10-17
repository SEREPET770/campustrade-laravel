<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | CampusTrade')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/admin.css'])
    @stack('styles')
</head>

<body class="admin-body">
    @php
        $menungguVerifikasi = \App\Models\User::where('role', 'user')->where('status_verifikasi', 'menunggu')->count();
        $admin = auth()->user();
    @endphp

    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="side-logo">
            <span class="logo-campus">campus</span><span class="logo-trade">trade</span>
        </a>

        <nav class="side-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="side-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <i class="fa-solid fa-house"></i><span class="lbl">Dashboard</span>
            </a>
            <a href="{{ route('admin.pengguna.index') }}"
                class="side-link {{ request()->routeIs('admin.pengguna.*') ? 'is-active' : '' }}">
                <i class="fa-solid fa-user-check"></i><span class="lbl">Verifikasi Pengguna</span>
                @if ($menungguVerifikasi > 0)
                    <span class="count lbl">{{ $menungguVerifikasi }}</span>
                @endif
            </a>

            {{-- Modul berikut belum dibangun: tampil nonaktif, aktifkan saat route-nya siap --}}
            <span class="side-link is-disabled"><i class="fa-solid fa-cube"></i><span class="lbl">Manajemen
                    Produk</span><span class="soon lbl">Segera</span></span>
            <span class="side-link is-disabled"><i class="fa-solid fa-tags"></i><span class="lbl">Kategori &amp;
                    Lokasi</span><span class="soon lbl">Segera</span></span>
            <span class="side-link is-disabled"><i class="fa-solid fa-cart-shopping"></i><span
                    class="lbl">Pesanan</span><span class="soon lbl">Segera</span></span>
            <span class="side-link is-disabled"><i class="fa-solid fa-file-lines"></i><span
                    class="lbl">Laporan</span><span class="soon lbl">Segera</span></span>
            <span class="side-link is-disabled"><i class="fa-solid fa-clock-rotate-left"></i><span class="lbl">Log
                    Aktivitas</span><span class="soon lbl">Segera</span></span>
            <span class="side-link is-disabled"><i class="fa-solid fa-gear"></i><span class="lbl">Pengaturan
                    Sistem</span><span class="soon lbl">Segera</span></span>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="side-logout">
            @csrf
            <button type="submit" class="side-link">
                <i class="fa-solid fa-right-from-bracket"></i><span class="lbl">Keluar</span>
            </button>
        </form>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <span class="topbar-title">Panel Admin</span>
            <div class="topbar-right">
                <a href="{{ route('admin.pengguna.index') }}" class="bell" aria-label="Pengguna menunggu verifikasi">
                    <i class="fa-regular fa-bell"></i>
                    @if ($menungguVerifikasi > 0)
                        <span class="bell-count">{{ $menungguVerifikasi }}</span>
                    @endif
                </a>
                <div class="topbar-profile">
                    <div class="avatar-circle">{{ mb_strtoupper(mb_substr($admin->nama, 0, 1)) }}</div>
                    <div>
                        <p class="profile-name">{{ $admin->nama }}</p>
                        <p class="profile-role">Administrator</p>
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
                warning: '⚠️'
            };
            const container = document.getElementById('popupContainer');
            const item = document.createElement('div');
            item.className = `popup-item popup-${type}`;
            item.innerHTML = `<span>${icons[type] ?? ''}</span><span>${message}</span>`;
            container.appendChild(item);
            setTimeout(() => item.remove(), 3000);
        }
        @if (session('notif'))
            showPopup(@json(session('notif')['pesan']), @json(session('notif')['tipe']));
        @endif
    </script>
    @stack('scripts')
</body>

</html>
