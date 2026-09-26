@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="page-wrap">
        <div class="page-header">
            <h1>Admin Dashboard</h1>
            <p>Ringkasan pengelolaan CampusTrade</p>
        </div>
        <div class="card">
            <a href="{{ route('admin.pengguna.index') }}" style="color:var(--color-primary); font-weight:600;">Kelola Pengguna
                →</a>
        </div>
    </div>
@endsection
