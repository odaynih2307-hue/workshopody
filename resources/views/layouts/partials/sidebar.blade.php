<div class="sidebar text-white">

    <h4 class="text-center text-warning fw-bold mb-4">
        📚 Koleksi Buku
    </h4>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i> Kategori
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('buku.index') }}" class="nav-link {{ request()->is('buku*') ? 'active' : '' }}">
                <i class="bi bi-book me-2"></i> Buku
            </a>
        </li>

    </ul>

</div>
