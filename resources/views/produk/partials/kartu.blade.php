<a href="{{ route('produk.show', $item) }}" class="product-card">
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
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 21s-7-6.5-7-11a7 7 0 0 1 14 0c0 4.5-7 11-7 11z" />
                <circle cx="12" cy="10" r="2.5" />
            </svg>
            {{ $item->lokasi->nama_lokasi ?? 'Tidak diketahui' }}
        </p>
        <p class="product-price">{{ $item->harga_format }}</p>
    </div>
</a>
