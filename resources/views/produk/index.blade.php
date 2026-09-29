@extends('layouts.site')

@section('title', ($search !== '' ? 'Cari: ' . $search : 'Semua Produk') . ' — CampusTrade')

@push('styles')
    @vite(['resources/css/produk.css'])
@endpush

@section('content')
    <div class="produk-layout">
        <form method="GET" action="{{ route('produk.index') }}" id="filter-form" class="filter-sidebar">
            @if ($search !== '')
                <input type="hidden" name="search" value="{{ $search }}">
            @endif

            <div class="filter-block">
                <h3 class="filter-title">Kategori</h3>
                @foreach ($kategoriList as $kat)
                    <label class="filter-check">
                        <input type="checkbox" name="kategori[]" value="{{ $kat->id_kategori }}"
                            onchange="this.form.submit()"
                            {{ in_array($kat->id_kategori, $kategoriDipilih) ? 'checked' : '' }}>
                        {{ $kat->nama_kategori }} <span>({{ $kat->produk_count }})</span>
                    </label>
                @endforeach
            </div>

            <div class="filter-block">
                <h3 class="filter-title">Kondisi</h3>
                <label class="filter-radio">
                    <input type="radio" name="kondisi" value="" onchange="this.form.submit()"
                        {{ !$kondisi ? 'checked' : '' }}>
                    Semua Kondisi <span>({{ $jumlahSemua }})</span>
                </label>
                <label class="filter-radio">
                    <input type="radio" name="kondisi" value="Baru" onchange="this.form.submit()"
                        {{ $kondisi === 'Baru' ? 'checked' : '' }}>
                    Baru <span>({{ $jumlahBaru }})</span>
                </label>
                <label class="filter-radio">
                    <input type="radio" name="kondisi" value="Bekas" onchange="this.form.submit()"
                        {{ $kondisi === 'Bekas' ? 'checked' : '' }}>
                    Bekas <span>({{ $jumlahBekas }})</span>
                </label>
            </div>

            <div class="filter-block">
                <h3 class="filter-title">Lokasi</h3>
                @foreach ($lokasiList as $lok)
                    <label class="filter-check">
                        <input type="checkbox" name="lokasi[]" value="{{ $lok->id_lokasi }}" onchange="this.form.submit()"
                            {{ in_array($lok->id_lokasi, $lokasiDipilih) ? 'checked' : '' }}>
                        {{ $lok->nama_lokasi }} <span>({{ $lok->produk_count }})</span>
                    </label>
                @endforeach
            </div>

            <div class="filter-block">
                <h3 class="filter-title">Harga</h3>
                <div class="filter-harga">
                    <input type="number" name="harga_min" placeholder="Rp Min" value="{{ $hargaMin }}" min="0">
                    <input type="number" name="harga_max" placeholder="Rp Maks" value="{{ $hargaMax }}"
                        min="0">
                </div>
                <button type="submit" class="btn-primary filter-apply">Terapkan Filter</button>
            </div>
        </form>

        <div class="produk-main">
            <div class="produk-toolbar">
                <label class="urutkan-label">
                    Urutkan
                    <select name="urutkan" form="filter-form" onchange="this.form.submit()">
                        <option value="terbaru" {{ $urutkan === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="harga_asc" {{ $urutkan === 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_desc" {{ $urutkan === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi
                        </option>
                    </select>
                </label>
            </div>

            <div class="product-grid">
                @forelse ($produk as $item)
                    <a href="#" class="product-card">
                        <div class="card-image">
                            @if ($item->foto_utama)
                                <img src="{{ $item->foto_utama }}" alt="{{ $item->nama_produk }}">
                            @else
                                <div class="img-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <circle cx="8.5" cy="9.5" r="1.5" />
                                        <path d="M21 16l-5-5-4 4-2-2-5 5" />
                                    </svg>
                                </div>
                            @endif
                            <span class="badge-kondisi {{ $item->kondisi === 'Baru' ? 'badge-baru' : 'badge-bekas' }}">
                                {{ $item->kondisi }}
                            </span>
                        </div>
                        <div class="card-content">
                            <h3 class="product-name">{{ $item->nama_produk }}</h3>
                            <p class="product-location">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21s-7-6.5-7-11a7 7 0 0 1 14 0c0 4.5-7 11-7 11z" />
                                    <circle cx="12" cy="10" r="2.5" />
                                </svg>
                                {{ $item->lokasi->nama_lokasi ?? 'Tidak diketahui' }}
                            </p>
                            <p class="product-price">{{ $item->harga_format }}</p>
                            <div class="product-footer">
                                <span class="product-seller">{{ $item->penjual->nama ?? '-' }}</span>
                                @if ($item->penjual?->no_whatsapp)
                                    <span class="wa-icon" title="Hubungi via WhatsApp">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 3a8 8 0 0 0-6.9 12l-1 4 4.1-1A8 8 0 1 0 12 3z" />
                                            <path
                                                d="M9 9.5c0 3 2.5 5.5 5.5 5.5.5 0 1-.4 1-1v-.8c0-.3-.2-.6-.5-.7l-1.4-.5c-.3-.1-.6 0-.8.2l-.3.4c-1-.5-1.8-1.3-2.3-2.3l.4-.3c.2-.2.3-.5.2-.8l-.5-1.4c-.1-.3-.4-.5-.7-.5H9.9c-.5 0-.9.4-.9 1z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <p>Tidak ada produk yang cocok dengan filter kamu.</p>
                    </div>
                @endforelse
            </div>

            @if ($produk->hasPages())
                <div class="pagination-bar">
                    <span class="pagination-info">
                        Menampilkan {{ $produk->firstItem() }}–{{ $produk->lastItem() }} dari {{ $produk->total() }}
                        produk
                    </span>
                    <div class="pagination-links">
                        @if ($produk->onFirstPage())
                            <span class="page-btn disabled">‹</span>
                        @else
                            <a href="{{ $produk->previousPageUrl() }}" class="page-btn">‹</a>
                        @endif

                        @for ($p = 1; $p <= $produk->lastPage(); $p++)
                            <a href="{{ $produk->url($p) }}"
                                class="page-btn {{ $p === $produk->currentPage() ? 'is-active' : '' }}">{{ $p }}</a>
                        @endfor

                        @if ($produk->hasMorePages())
                            <a href="{{ $produk->nextPageUrl() }}" class="page-btn">›</a>
                        @else
                            <span class="page-btn disabled">›</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
