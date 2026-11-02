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
                <div class="brand-name">
                    <span class="brand-campus">campus</span><span class="brand-trade">trade</span>
                </div>
            </div>



            @if (session('notif'))
                @php
                    $notif = session('notif');
                    $notifIcon = ['success' => '✓', 'error' => '!', 'warning' => '!', 'info' => 'ⓘ'];
                @endphp
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

            @env('local')
                @php
                    $akunUji = [
                        ['Admin', 'admin@campustrade.test'],
                        ['Rizky Aditya · penjual/pembeli', 'rizky@campustrade.test'],
                        ['Dinda Salsabila · penjual/pembeli', 'dinda@campustrade.test'],
                        ['Fajar Maulana · penjual/pembeli', 'fajar@campustrade.test'],
                        ['Nabila Rahma · penjual/pembeli', 'nabila@campustrade.test'],
                    ];
                    $akunTolak = [
                        ['Menunggu verifikasi', 'uji.menunggu@campustrade.test'],
                        ['Akun ditolak', 'uji.ditolak@campustrade.test'],
                        ['Akun nonaktif', 'uji.nonaktif@campustrade.test'],
                    ];
                @endphp

                <div class="demo-accounts">
                    <p class="demo-title">Akun uji (hanya tampil di lokal)</p>
                    <ul>
                        @foreach ($akunUji as [$peran, $email])
                            <li>
                                <div>
                                    <strong>{{ $peran }}</strong>
                                    <span>{{ $email }}</span>
                                </div>
                                <button type="button" class="demo-fill" data-email="{{ $email }}">Isi</button>
                            </li>
                        @endforeach
                    </ul>

                    <details class="demo-more">
                        <summary>Akun uji penolakan login</summary>
                        <ul>
                            @foreach ($akunTolak as [$peran, $email])
                                <li>
                                    <div>
                                        <strong>{{ $peran }}</strong>
                                        <span>{{ $email }}</span>
                                    </div>
                                    <button type="button" class="demo-fill" data-email="{{ $email }}">Isi</button>
                                </li>
                            @endforeach
                        </ul>
                    </details>

                    <p class="demo-pass">Password semua akun: <code>password</code></p>
                </div>

                <script>
                    document.querySelectorAll('.demo-fill').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            document.getElementById('email').value = btn.dataset.email;
                            const password = document.getElementById('password');
                            password.value = 'password';
                            password.focus();
                        });
                    });
                </script>
            @endenv
        </div>
    </main>

    <footer class="auth-footer">
        © {{ date('Y') }} CampusTrade. Bersama mewujudkan kampus yang lebih baik.
    </footer>

</body>

</html>
