<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CampusTrade')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body>

    @auth
    <nav class="top-nav">
        <span class="brand">CampusTrade <span class="role-chip">{{ auth()->user()->isAdmin() ? 'Admin' : 'Mahasiswa' }}</span></span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </nav>
    @endauth

    <main>
        @yield('content')
    </main>

    <div id="popupContainer" style="position:fixed; top:24px; right:24px; z-index:9999; pointer-events:none;"></div>
    <script>
        function showPopup(message, type = 'success') {
            const icons = { success: '✅', error: '❌', warning: '⚠️' };
            const container = document.getElementById('popupContainer');
            const item = document.createElement('div');
            item.className = `popup-item popup-${type}`;
            item.style.pointerEvents = 'auto';
            item.innerHTML = `<span>${icons[type] ?? ''}</span><span>${message}</span>`;
            container.appendChild(item);
            setTimeout(() => item.remove(), 3000);
        }
        @if(session('notif'))
            showPopup(@json(session('notif')['pesan']), @json(session('notif')['tipe']));
        @endif
    </script>
    @stack('scripts')
</body>
</html>