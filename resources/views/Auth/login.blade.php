<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - CampusTrade</title>
    @vite(['resources/css/auth.css', 'resources/js/app.js'])
</head>

<body class="auth-page">

    <div class="auth-overlay"></div>

    <main class="auth-container">
        <div class="auth-card login-card">
            <div class="auth-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 2L31 9.5V24.5L17 32L3 24.5V9.5L17 2Z" stroke="#ffffff" stroke-width="2"
                            stroke-linejoin="round" />
                        <path d="M17 2L31 9.5L17 17L3 9.5L17 2Z" fill="#42a5f5" fill-opacity="0.55" stroke="#42a5f5"
                            stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="brand-name">
                    Campus<span>Trade</span>
                </div>
            </div>



            @if (session('notif'))
                @php($notif = session('notif'))
                @php($notifIcon = ['success' => '✓', 'error' => '!', 'warning' => '!', 'info' => 'ⓘ'])
                <div class="alert alert-{{ $notif['tipe'] }}">
                    <span>{{ $notifIcon[$notif['tipe']] ?? '•' }}</span>
                    {{ $notif['pesan'] }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <span>!</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="M3 7l9 6 9-6" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="Masukkan email kampus" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="11" width="14" height="9" rx="2" />
                                <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle" data-target="password">
                            ◉
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="auth-button">
                    Masuk
                </button>
            </form>

            <div class="auth-divider">
                <span></span>
                <small>atau</small>
                <span></span>
            </div>

            <p class="auth-switch">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar</a>
            </p>
        </div>
    </main>

    <footer class="auth-footer">
        © {{ date('Y') }} CampusTrade. Bersama mewujudkan kampus yang lebih baik.
    </footer>

</body>

</html>
