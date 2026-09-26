<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - CampusTrade</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page">

    <div class="auth-overlay"></div>

    <main class="auth-container">
        <div class="auth-card login-card">
            <div class="auth-brand">
                <div class="brand-icon">C</div>
                <div class="brand-name">
                    Campus<span>Trade</span>
                </div>
            </div>

            <div class="auth-heading">
                <h1>Masuk ke CampusTrade</h1>
                <p>Temukan dan jual barang di lingkungan kampus</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <span>✓</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    <span>!</span>
                    {{ session('error') }}
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
                        <span class="input-icon">✉</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="Masukkan email kampus" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
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
                </div>

                <button type="submit" class="auth-button">
                    Masuk
                    <span>→</span>
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
