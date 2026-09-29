<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - CampusTrade</title>
    @vite(['resources/css/auth.css', 'resources/js/app.js'])
</head>

<body class="auth-page">

    <div class="auth-overlay"></div>

    <main class="auth-container">
        <div class="auth-card register-card">
            <div class="auth-brand">
                <div class="brand-name">
                    <span class="brand-campus">campus</span><span class="brand-trade">trade</span>
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

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 3.5-6 8-6s8 2 8 6" />
                            </svg>
                        </span>
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
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <circle cx="8.5" cy="11" r="1.8" />
                                    <path d="M5.5 16c.5-1.6 1.8-2.4 3-2.4s2.5.8 3 2.4" />
                                    <path d="M14 10h5M14 13.5h5" />
                                </svg>
                            </span>
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}"
                                placeholder="Masukkan NIM" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="no_whatsapp">WhatsApp</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3a8 8 0 0 0-6.9 12l-1 4 4.1-1A8 8 0 1 0 12 3z" />
                                    <path
                                        d="M9 9.5c0 3 2.5 5.5 5.5 5.5.5 0 1-.4 1-1v-.8c0-.3-.2-.6-.5-.7l-1.4-.5c-.3-.1-.6 0-.8.2l-.3.4c-1-.5-1.8-1.3-2.3-2.3l.4-.3c.2-.2.3-.5.2-.8l-.5-1.4c-.1-.3-.4-.5-.7-.5H9.9c-.5 0-.9.4-.9 1z" />
                                </svg>
                            </span>
                            <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
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

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 12a8 8 0 0 1 14-5.3M20 12a8 8 0 0 1-14 5.3" />
                                <path d="M18 3v4h-4M6 21v-4h4" />
                            </svg>
                        </span>
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
                        <span class="file-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="6" width="18" height="13" rx="2" />
                                <path d="M8 6l1.5-2h5L16 6" />
                                <circle cx="12" cy="12.5" r="3.2" />
                            </svg>
                        </span>
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
