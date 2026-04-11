<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- PROFILE --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
                    <span class="text-secondary text-small">Administrator</span>
                </div>
            </a>
        </li>

        {{-- DASHBOARD --}}
        <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- BUKU --}}
        <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>

        {{-- KATEGORI --}}
        <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>

        {{-- PDF SECTION --}}
        <li class="nav-item mt-3">
            <span class="nav-link text-muted">Generate PDF</span>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('/pdf/sertifikat') }}">
                <span class="menu-title">Sertifikat (Landscape)</span>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('/pdf/undangan') }}">
                <span class="menu-title">Undangan (Portrait)</span>
                <i class="mdi mdi-file-document menu-icon"></i>
            </a>
        </li>

        {{-- TAG HARGA --}}
        <li class="nav-item {{ Request::is('barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Tag Harga</span>
                <i class="mdi mdi-tag menu-icon"></i>
            </a>
        </li>

        {{-- FORM BARANG --}}
        <li class="nav-item mt-3">
            <span class="nav-link text-muted">Form Barang</span>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('barang.form-html') ? 'active' : '' }}" href="{{ route('barang.form-html') }}">
                <span class="menu-title">Form HTML Table</span>
                <i class="mdi mdi-table menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('barang.form-datatable') ? 'active' : '' }}" href="{{ route('barang.form-datatable') }}">
                <span class="menu-title">Form DataTables</span>
                <i class="mdi mdi-database menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('kota*') ? 'active' : '' }}">
             <a class="nav-link" href="{{ route('kota.index') }}">
             <span class="menu-title">Kota</span>
             <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>

        {{-- KANTIN SECTION --}}
        <li class="nav-item mt-3">
            <span class="nav-link text-muted">Master Kantin</span>
        </li>

        {{-- KANTIN --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('kantin.pos') ? 'active' : '' }}" href="{{ route('kantin.pos') }}">
                <span class="menu-title">Kantin</span>
                <i class="mdi mdi-store menu-icon"></i>
            </a>
        </li>

        {{-- KELOLA KANTIN --}}
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-kelola-kantin" aria-expanded="false" aria-controls="ui-kelola-kantin">
                <span class="menu-title">Kelola Kantin</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-silverware menu-icon"></i>
            </a>
            <div class="collapse" id="ui-kelola-kantin">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link {{ Request::routeIs('kantin.vendor.*') ? 'active' : '' }}" href="{{ route('kantin.vendor.index') }}">Vendor</a></li>
                    <li class="nav-item"> <a class="nav-link {{ Request::routeIs('kantin.menu.*') ? 'active' : '' }}" href="{{ route('kantin.menu.index') }}">Menu</a></li>
                    <li class="nav-item"> <a class="nav-link {{ Request::routeIs('kantin.pesanan.*') ? 'active' : '' }}" href="{{ route('kantin.pesanan.index') }}">Data Pesanan</a></li>
                </ul>
            </div>
        </li>

        {{-- STUDI KASUS SECTION --}}
        <li class="nav-item mt-3">
            <span class="nav-link text-muted">Tugas Baru</span>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('wilayah.cascading-form') ? 'active' : '' }}" href="{{ route('wilayah.cascading-form') }}">
                <span class="menu-title">Cascading Select Wilayah</span>
                <i class="mdi mdi-checkbox-marked menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('kasir.index') ? 'active' : '' }}" href="{{ route('kasir.index') }}">
                <span class="menu-title">Point of Sales (POS)</span>
                <i class="mdi mdi-cash-register menu-icon"></i>
            </a>
        </li>

        {{-- LOGOUT --}}
        <li class="nav-item mt-4">
            <a class="nav-link"
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-title">Logout</span>
                <i class="mdi mdi-logout menu-icon"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</nav>