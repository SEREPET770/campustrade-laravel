<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - CampusTrade</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page">

    <div class="auth-overlay"></div>

    <main class="auth-container">
        <div class="auth-card register-card">
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

            <div class="auth-heading">
                <h1>Daftar Akun</h1>
                <p>Buat akun untuk mulai menggunakan CampusTrade</p>
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

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <span class="input-icon">♙</span>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Kampus</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="contoh@unusa.ac.id" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <div class="input-wrapper">
                            <span class="input-icon">#</span>
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}"
                                placeholder="Masukkan NIM" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="no_whatsapp">WhatsApp</label>
                        <div class="input-wrapper">
                            <span class="input-icon">◉</span>
                            <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
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

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password" required>
                        <button type="button" class="password-toggle" data-target="password_confirmation">
                            ◉
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="foto_ktm">Foto KTM</label>
                    <label class="file-upload" for="foto_ktm">
                        <span class="file-icon">▣</span>
                        <span id="file-name">Pilih foto KTM</span>
                    </label>
                    <input type="file" id="foto_ktm" name="foto_ktm" accept="image/jpeg,image/png,image/jpg"
                        required hidden>
                    <small class="file-info">
                        Format JPG, JPEG, PNG. Maksimal 2MB.
                    </small>
                </div>

                <button type="submit" class="auth-button">
                    Daftar
                </button>
            </form>

            <div class="auth-divider">
                <span></span>
                <small>atau</small>
                <span></span>
            </div>

            <p class="auth-switch">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk</a>
            </p>

            <div class="verification-info">
                <span>ⓘ</span>
                <p>Setelah mendaftar, akun Anda akan diverifikasi oleh admin sebelum dapat digunakan.</p>
            </div>
        </div>
    </main>

    <footer class="auth-footer">
        © {{ date('Y') }} CampusTrade. Bersama mewujudkan kampus yang lebih baik.
    </footer>

</body>

</html>
