@extends('layouts.app')
@section('title', 'Register — CampusTrade')

@section('content')
    <div class="auth-page">
        <div class="auth-wrapper">
            <div class="auth-left">
                <div class="auth-brand">
                    <h1>Campus<br>Trade</h1>
                    <p>Platform jual-beli eksklusif khusus mahasiswa terverifikasi.</p>
                </div>
                <span class="auth-trust-chip">🎓 Verifikasi KTM wajib</span>
            </div>

            <div class="auth-right">
                <div class="auth-tabs">
                    <a href="{{ route('register') }}" class="active">Buat Akun</a>
                    <a href="{{ route('login') }}">Login</a>
                </div>

                <form class="auth-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <ul class="form-error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="field">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required minlength="8">
                    </div>
                    <div class="field">
                        <label for="nim">NIM</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim') }}" required>
                    </div>
                    <div class="field">
                        <label for="no_whatsapp">No. WhatsApp</label>
                        <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required>
                    </div>
                    <div class="field">
                        <label for="foto_ktm">Foto KTM (JPG/PNG, maks 2MB)</label>
                        <input type="file" id="foto_ktm" name="foto_ktm" accept=".jpg,.jpeg,.png" required>
                    </div>

                    <button type="submit" class="btn-submit">Daftar</button>
                    <p style="font-size:13px;color:var(--color-ink-soft);">Sudah punya akun? <a href="{{ route('login') }}"
                            style="color:var(--color-primary);font-weight:600;">Login</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection
