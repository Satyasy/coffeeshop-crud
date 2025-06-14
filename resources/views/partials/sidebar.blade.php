<aside style="min-width: 260px; max-width: 260px; background-color: var(--sidebar-bg); transition: all 0.3s;">
    <div class="p-4">
        <a class="navbar-brand text-white" href="{{ route('admin.dashboard') }}">Coffee<small>Blend</small></a>
    </div>
    <nav class="p-3">
        <p class="text-secondary text-uppercase small font-weight-bold">Manajemen</p>
        <ul class="list-unstyled">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <span class="oi oi-home mr-2"></span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <span class="oi oi-people mr-2"></span> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.menus*') ? 'active' : '' }}"
                    href="{{ route('admin.menus.index') }}">
                    <span class="oi oi-book mr-2"></span> Menus
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}">
                    <span class="oi oi-cart mr-2"></span> Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}"
                    href="{{ route('admin.payments.index') }}">
                    <span class="oi oi-dollar mr-2"></span> Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}"
                    href="{{ route('admin.reviews.index') }}">
                    <span class="oi oi-star mr-2"></span> Reviews
                </a>
            </li>
        </ul>
    </nav>
    <div class="p-3 mt-auto">
        <hr style="border-color: rgba(255,255,255,0.1);">
        <ul class="list-unstyled">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <span class="oi oi-globe mr-2"></span> View Public Site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="oi oi-account-logout mr-2"></span> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</aside>

<style>
    .nav-item .nav-link {
        color: var(--sidebar-text);
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease-in-out;
        font-weight: 500;
    }

    .nav-item .nav-link:hover {
        color: var(--sidebar-text-hover);
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-item .nav-link.active {
        color: var(--sidebar-text-hover);
        background-color: var(--sidebar-active-bg);
    }

    aside {
        display: flex;
        flex-direction: column;
    }
</style>
