@extends('layouts.admin')
@section('title', 'Dashboard | Admin')

@section('content')
    @php
        $badgeUser = ['terverifikasi' => 'badge-success', 'menunggu' => 'badge-warning', 'ditolak' => 'badge-danger'];
        $badgeProduk = [
            'tersedia' => 'badge-success',
            'menunggu' => 'badge-warning',
            'dipesan' => 'badge-info',
            'terjual' => 'badge-muted',
            'ditolak' => 'badge-danger',
        ];
        $persen = fn($n) => $totalUser > 0 ? round(($n / $totalUser) * 100, 1) : 0;
    @endphp

    <div class="dash-head">
        <div>
            <h1>Selamat datang, {{ auth()->user()->nama }}!</h1>
            <p>Berikut ringkasan aktivitas dan data terbaru di CampusTrade.</p>
        </div>
        <div class="date-card">
            <i class="fa-regular fa-calendar"></i>{{ now()->locale('id')->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <div class="stat-grid">
        <a href="{{ route('admin.pengguna.index') }}" class="stat-card tone-blue">
            <span class="stat-icon"><i class="fa-solid fa-user-group"></i></span>
            <div>
                <p class="stat-label">Menunggu Verifikasi</p>
                <p class="stat-value">{{ $menunggu }}</p>
                <p class="stat-sub">{{ $penggunaBulanIni }} pendaftar bulan ini</p>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>

        <a href="{{ route('admin.produk.index', ['status' => 'tersedia']) }}" class="stat-card tone-teal">
            <span class="stat-icon"><i class="fa-solid fa-cube"></i></span>
            <div>
                <p class="stat-label">Produk Tersedia</p>
                <p class="stat-value">{{ $produkTersedia }}</p>
                <p class="stat-sub">{{ $produkBaruBulanIni }} produk baru bulan ini</p>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>

        <a href="{{ route('admin.produk.index', ['status' => 'terjual']) }}" class="stat-card tone-purple">
            <span class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></span>
            <div>
                <p class="stat-label">Produk Terjual</p>
                <p class="stat-value">{{ $produkTerjual }}</p>
                <p class="stat-sub">{{ $terjualBulanIni }} terjual bulan ini</p>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>

        <div class="stat-card tone-amber">
            <span class="stat-icon"><i class="fa-solid fa-table-cells-large"></i></span>
            <div>
                <p class="stat-label">Kategori</p>
                <p class="stat-value">{{ $totalKategori }}</p>
                <p class="stat-sub">kategori terdaftar</p>
            </div>
        </div>

        <div class="stat-card tone-mint">
            <span class="stat-icon"><i class="fa-solid fa-location-dot"></i></span>
            <div>
                <p class="stat-label">Lokasi</p>
                <p class="stat-value">{{ $totalLokasi }}</p>
                <p class="stat-sub">lokasi kampus</p>
            </div>
        </div>
    </div>

    <div class="dash-grid dash-grid-wide">
        <section class="panel">
            <div class="panel-head">
                <h2>Aktivitas Pengguna</h2>
                <div class="range-tabs" id="rangeTabs">
                    <button type="button" class="range-tab is-active" data-range="7">7 Hari</button>
                    <button type="button" class="range-tab" data-range="30">30 Hari</button>
                    <button type="button" class="range-tab" data-range="90">90 Hari</button>
                </div>
            </div>
            <div class="chart-box" id="chartAktivitas"></div>
            <div class="chart-legend">
                <span><i class="dot" style="background:#1d6fa5"></i>Pengguna Baru</span>
                <span><i class="dot" style="background:#10a37f"></i>Pesanan Dibuat</span>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Status Verifikasi Pengguna</h2>
            </div>
            <div class="donut-wrap">
                <div class="donut-box" id="donutVerifikasi"></div>
                <ul class="donut-legend">
                    @foreach ($donut as $d)
                        <li>
                            <i class="dot" style="background:{{ $d['color'] }}"></i>
                            <span class="lg-text">{{ $d['label'] }}</span>
                            <strong>{{ $d['value'] }}</strong>
                            <span class="pct">{{ $persen($d['value']) }}%</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </div>

    <div class="dash-grid">
        <section class="panel">
            <div class="panel-head">
                <h2>Pengguna Terbaru</h2>
                <a href="{{ route('admin.pengguna.index') }}" class="see-all">Lihat Semua →</a>
            </div>
            <div class="table-scroll">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>NIM</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunaTerbaru as $u)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar-circle sm">{{ mb_strtoupper(mb_substr($u->nama, 0, 1)) }}</div>
                                        <div>
                                            <div class="nama">{{ $u->nama }}</div>
                                            <div class="email">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $u->nim }}</td>
                                <td><span
                                        class="badge {{ $badgeUser[$u->status_verifikasi] ?? 'badge-muted' }}">{{ ucfirst($u->status_verifikasi) }}</span>
                                </td>
                                <td>{{ $u->created_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Produk Terbaru</h2>
                <a href="{{ route('admin.produk.index', ['status' => 'semua']) }}" class="see-all">Lihat Semua →</a>
            </div>
            <div class="table-scroll">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produkTerbaru as $p)
                            <tr>
                                <td>
                                    @if ($p->foto_utama)
                                        <img src="{{ $p->foto_utama }}" alt="" class="thumb-sm">
                                    @else
                                        <div class="thumb-sm thumb-empty"><i class="fa-regular fa-image"></i></div>
                                    @endif
                                </td>
                                <td><a href="{{ route('admin.produk.show', $p) }}"
                                        class="nama">{{ $p->nama_produk }}</a></td>
                                <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                                <td><span
                                        class="badge {{ $badgeProduk[$p->status_produk] ?? 'badge-muted' }}">{{ ucfirst($p->status_produk) }}</span>
                                </td>
                                <td>{{ $p->created_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="dash-grid">
        <section class="panel">
            <div class="panel-head">
                <h2>Ringkasan Pesanan COD</h2>
                <a href="{{ route('admin.pesanan.index') }}" class="see-all">Lihat Semua →</a>
            </div>
            <div class="cod-grid">
                <div class="cod-tile tone-blue">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <p class="cod-label">Total Pesanan</p>
                    <p class="cod-value">{{ $ringkasanPesanan['total'] }}</p>
                </div>
                <div class="cod-tile tone-amber">
                    <i class="fa-regular fa-clock"></i>
                    <p class="cod-label">Menunggu Konfirmasi</p>
                    <p class="cod-value">{{ $ringkasanPesanan['menunggu'] }}</p>
                </div>
                <div class="cod-tile tone-mint">
                    <i class="fa-solid fa-truck"></i>
                    <p class="cod-label">Sedang Diproses</p>
                    <p class="cod-value">{{ $ringkasanPesanan['diproses'] }}</p>
                </div>
                <div class="cod-tile tone-purple">
                    <i class="fa-solid fa-circle-check"></i>
                    <p class="cod-label">Selesai</p>
                    <p class="cod-value">{{ $ringkasanPesanan['selesai'] }}</p>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Aktivitas Admin Terbaru</h2>
            </div>
            <ul class="activity">
                @forelse ($aktivitas as $log)
                    @php
                        $untukProduk = $log->id_produk !== null;
                        $setuju = $log->status === 'terverifikasi';
                        $subjek = $untukProduk
                            ? $log->produk->nama_produk ?? 'yang sudah dihapus'
                            : $log->pengguna->nama ?? 'yang sudah dihapus';
                    @endphp
                    <li>
                        <span class="act-icon {{ $setuju ? 'is-ok' : 'is-no' }}">
                            <i class="fa-solid {{ $untukProduk ? 'fa-cube' : 'fa-user-check' }}"></i>
                        </span>
                        <p>
                            {{ $log->admin->nama ?? 'Admin' }}
                            {{ $setuju ? 'memverifikasi' : 'menolak' }}
                            {{ $untukProduk ? 'produk' : 'pengguna' }}
                            <strong>{{ $subjek }}</strong>
                        </p>
                        <time>{{ $log->created_at?->locale('id')->translatedFormat('d M, H:i') }}</time>
                    </li>
                @empty
                    <li class="empty">Belum ada aktivitas verifikasi.</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection

@push('scripts')
    @include('partials.dash-charts')
    <script>
        (() => {
            const data = @json($grafik);
            const box = document.getElementById('chartAktivitas');
            const tampil = (n) => CTChart.line(box, {
                labels: data.labels.slice(-n),
                series: [{
                        name: 'Pengguna Baru',
                        color: '#1d6fa5',
                        values: data.pengguna.slice(-n)
                    },
                    {
                        name: 'Pesanan Dibuat',
                        color: '#10a37f',
                        values: data.pesanan.slice(-n)
                    },
                ],
            });

            tampil(7);
            const tabs = document.querySelectorAll('#rangeTabs .range-tab');
            tabs.forEach((btn) => btn.addEventListener('click', () => {
                tabs.forEach((b) => b.classList.toggle('is-active', b === btn));
                tampil(Number(btn.dataset.range));
            }));

            CTChart.donut(document.getElementById('donutVerifikasi'), @json($donut),
                @json($totalUser), 'Total Pengguna');
        })();
    </script>
@endpush
