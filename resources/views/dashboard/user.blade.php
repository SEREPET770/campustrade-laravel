@extends('layouts.penjual')
@section('title', 'Dashboard | CampusTrade')

@section('content')
    @php
        $rupiah = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
        $badgePesanan = [
            'menunggu' => 'badge-warning',
            'dikonfirmasi' => 'badge-info',
            'dikirim' => 'badge-info',
            'selesai' => 'badge-success',
            'ditolak' => 'badge-danger',
            'dibatalkan' => 'badge-muted',
        ];
        $badgeProduk = [
            'tersedia' => 'badge-success',
            'menunggu' => 'badge-warning',
            'dipesan' => 'badge-info',
            'terjual' => 'badge-muted',
            'ditolak' => 'badge-danger',
        ];
        $totalDonut = collect($donut)->sum('value');
        $persen = fn($n) => $totalDonut > 0 ? round(($n / $totalDonut) * 100) : 0;
    @endphp

    <div class="dash-head">
        <div>
            <h1>Halo, {{ auth()->user()->nama }}!</h1>
            <p>Berikut ringkasan toko dan penjualanmu hari ini.</p>
        </div>
        <div class="date-card">
            <i class="fa-regular fa-calendar"></i>{{ now()->locale('id')->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <div class="stat-grid stat-grid-4">
        <a href="{{ route('penjual.produk.index') }}" class="stat-card tone-blue">
            <span class="stat-icon"><i class="fa-solid fa-box"></i></span>
            <div>
                <p class="stat-label">Total Produk</p>
                <p class="stat-value">{{ $ringkasan['totalProduk'] }}</p>
                <p class="stat-sub">{{ $ringkasan['produkBaruBulanIni'] }} produk baru bulan ini</p>
            </div>
        </a>

        <a href="{{ route('penjual.pesanan.index', ['status' => 'selesai']) }}" class="stat-card tone-mint">
            <span class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></span>
            <div>
                <p class="stat-label">Produk Terjual</p>
                <p class="stat-value">{{ $ringkasan['produkTerjual'] }}</p>
                <p class="stat-sub">{{ $ringkasan['terjualBulanIni'] }} terjual bulan ini</p>
            </div>
        </a>

        <a href="{{ route('penjual.pesanan.index', ['status' => 'menunggu']) }}" class="stat-card tone-purple">
            <span class="stat-icon"><i class="fa-solid fa-bag-shopping"></i></span>
            <div>
                <p class="stat-label">Pesanan Baru</p>
                <p class="stat-value">{{ $ringkasan['pesananBaru'] }}</p>
                <p class="stat-sub">{{ $ringkasan['pesananHariIni'] }} pesanan hari ini</p>
            </div>
        </a>

        <div class="stat-card tone-amber">
            <span class="stat-icon"><i class="fa-solid fa-wallet"></i></span>
            <div>
                <p class="stat-label">Pendapatan</p>
                <p class="stat-value is-money">{{ $rupiah($ringkasan['pendapatan']) }}</p>
                <p class="stat-sub">{{ $rupiah($ringkasan['pendapatanBulanIni']) }} bulan ini</p>
            </div>
        </div>
    </div>

    <div class="dash-grid dash-grid-wide">
        <section class="panel">
            <div class="panel-head">
                <h2>Penjualan 7 Hari Terakhir</h2>
            </div>
            <div class="chart-box" id="chartPenjualan"></div>
            <div class="chart-legend">
                <span><i class="dot" style="background:#2f80ed"></i>Pesanan Masuk</span>
                <span><i class="dot" style="background:#10b981"></i>Produk Terjual</span>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Status Pesanan</h2>
            </div>
            <div class="donut-wrap">
                <div class="donut-box" id="donutPesanan"></div>
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
                <h2>Produk Saya</h2>
                <a href="{{ route('penjual.produk.index') }}" class="see-all">Lihat Semua →</a>
            </div>
            <div class="table-scroll">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Status</th>
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
                                <td class="nama">{{ $p->nama_produk }}</td>
                                <td>{{ $p->harga_format }}</td>
                                <td><span
                                        class="badge {{ $badgeProduk[$p->status_produk] ?? 'badge-muted' }}">{{ ucfirst($p->status_produk) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">Belum ada produk. Mulai dengan menambahkan produk pertama
                                    Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Pesanan Terbaru</h2>
                <a href="{{ route('penjual.pesanan.index') }}" class="see-all">Lihat Semua →</a>
            </div>
            <div class="table-scroll">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Produk</th>
                            <th>Pembeli</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesananTerbaru as $o)
                            <tr>
                                <td><a href="{{ route('penjual.pesanan.show', $o) }}"
                                        class="soft">{{ $o->kode }}</a></td>
                                <td>{{ $o->nama_produk }}</td>
                                <td>{{ $o->pembeli->nama ?? '-' }}</td>
                                <td><span
                                        class="badge {{ $badgePesanan[$o->status] ?? 'badge-muted' }}">{{ $o->label_status }}</span>
                                </td>
                                <td>{{ $o->created_at->locale('id')->translatedFormat('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">Belum ada pesanan masuk.</td>
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
                <h2>Aktivitas Toko Terbaru</h2>
            </div>
            <ul class="activity">
                @forelse ($aktivitas as $a)
                    <li>
                        <span class="act-icon {{ $a['nada'] }}"><i class="fa-solid {{ $a['ikon'] }}"></i></span>
                        <p>{{ $a['teks'] }}</p>
                        <time>{{ $a['waktu']?->locale('id')->translatedFormat('d M, H:i') }}</time>
                    </li>
                @empty
                    <li class="empty">Belum ada aktivitas.</li>
                @endforelse
            </ul>
        </section>

        <section class="panel promo-panel">
            <i class="fa-solid fa-chart-line promo-ic"></i>
            <h2>Tingkatkan Penjualan Anda!</h2>
            <p>Lengkapi deskripsi, unggah foto yang jelas, dan jawab pesanan dengan cepat agar produk Anda lebih dipercaya
                pembeli.</p>
            <a href="{{ route('penjual.produk.create') }}" class="btn-cta">+ Tambah Produk</a>
        </section>
    </div>
@endsection

@push('scripts')
    @include('partials.dash-charts')
    <script>
        (() => {
            const data = @json($grafik);
            CTChart.line(document.getElementById('chartPenjualan'), {
                labels: data.labels,
                series: [{
                        name: 'Pesanan Masuk',
                        color: '#2f80ed',
                        values: data.masuk
                    },
                    {
                        name: 'Produk Terjual',
                        color: '#10b981',
                        values: data.terjual
                    },
                ],
            });

            CTChart.donut(document.getElementById('donutPesanan'), @json($donut),
                @json($ringkasan['pesananBaru']), 'Pesanan Baru');
        })();
    </script>
@endpush
