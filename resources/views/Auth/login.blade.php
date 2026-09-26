@extends('layouts.app')
@section('title', 'Login — CampusTrade')

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
                    <a href="{{ route('register') }}">Buat Akun</a>
                    <a href="{{ route('login') }}" class="active">Login</a>
                </div>

                <form class="auth-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    @if ($errors->any())
                        <p class="form-error">{{ $errors->first() }}</p>
                    @endif

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="nama@student.ac.id"
                            value="{{ old('email') }}" required>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-submit">Masuk</button>
                    <p style="font-size:13px;color:var(--color-ink-soft);">Belum punya akun? <a
                            href="{{ route('register') }}" style="color:var(--color-primary);font-weight:600;">Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection
