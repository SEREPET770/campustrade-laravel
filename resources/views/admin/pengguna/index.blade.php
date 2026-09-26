@extends('layouts.app')
@section('title', 'Pengguna | Admin')

@section('content')
    <div class="page-wrap">
        <div class="page-header">
            <h1>Pengguna</h1>
            <p>Verifikasi dan kelola akun pengguna CampusTrade</p>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ mb_strtoupper(mb_substr($user->nama, 0, 1)) }}</div>
                                    <div>
                                        <div class="nama">{{ $user->nama }}</div>
                                        <div class="email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->nim }}</td>
                            <td>
                                @if ($user->status_verifikasi === 'terverifikasi')
                                    <span class="badge badge-success">Terverifikasi</span>
                                @elseif ($user->status_verifikasi === 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->status_verifikasi === 'menunggu')
                                    <form method="POST" action="{{ route('admin.pengguna.approve', $user) }}"
                                        style="display:inline" onsubmit="return confirm('Verifikasi pengguna ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-verify">✓ Verifikasi</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pengguna.reject', $user) }}"
                                        style="display:inline" onsubmit="return confirm('Tolak pengguna ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-reject">✕ Tolak</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:var(--color-ink-soft);">Tidak ada pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">{{ $users->links() }}</div>
    </div>
@endsection
