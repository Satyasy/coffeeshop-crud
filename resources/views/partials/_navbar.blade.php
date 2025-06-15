<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Coffee<small>Blend</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                {{-- Link Halaman dibuat Dinamis --}}
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}"
                        class="nav-link">Home</a></li>
                <li class="nav-item {{ request()->routeIs('menu') ? 'active' : '' }}"><a href="{{ route('menu') }}"
                        class="nav-link">Menu</a></li>
                <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}"
                        class="nav-link">About</a></li>

                {{-- ======================================================= --}}
                {{-- === TAMBAHKAN BLOK INI UNTUK CART === --}}
                {{-- ======================================================= --}}
                @auth {{-- Hanya tampilkan jika user sudah login --}}
                    <li class="nav-item cart {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <span class="icon icon-shopping_cart"></span>
                            {{-- Tampilkan jumlah item jika ada --}}
                            @if(CartHelper::itemCount() > 0)
                                <span class="badge bg-primary rounded-circle" style="position: absolute; top: 10px; right: 0; font-size: 10px;">{{ CartHelper::itemCount() }}</span>
                            @endif
                        </a>
                    </li>
                @endauth
                {{-- ======================================================= --}}

                {{-- --- BLOK LOGIN & LOGOUT BARU --- --}}
                @guest
                    {{-- Tampilan untuk Pengunjung (belum login) --}}
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                @endguest

                @auth
                    {{-- Tampilan untuk Pengguna yang Sudah Login --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            @else
                                {{-- Nanti bisa ditambahkan link ke profil atau histori pesanan --}}
                                <a class="dropdown-item" href="#">My Profile</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endauth
                {{-- --- AKHIR BLOK LOGIN & LOGOUT --- --}}
            
            </ul>
        </div>
    </div>
</nav>