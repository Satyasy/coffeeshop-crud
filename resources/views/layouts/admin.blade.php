<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #c49b63;
            --bs-primary-rgb: 196, 155, 99;
            --sidebar-bg: #212529;
            --sidebar-width: 260px;
            --bs-body-bg: #f4f7fc;
            --bs-body-color: #5a5c69;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        /* DIPERBAIKI: Struktur layout utama */
        .admin-wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.toggled {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        #content-wrapper {
            width: 100%;
            padding: 0;
            transition: all 0.3s;
        }

        /* Styling Sidebar */
        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
        }

        #sidebar .nav-link .bi {
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        #sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        #sidebar .nav-link.active {
            color: #fff;
            background-color: var(--bs-primary);
        }

        .sidebar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            padding: 1.75rem 1.5rem;
            color: #fff;
            text-align: center;
        }

        /* Styling Topbar & Konten */
        .topbar {
            height: 70px;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0 2rem;
        }

        .container-fluid {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        @include('partials.sidebar') <div id="content-wrapper" class="d-flex flex-column">
            <main id="content">
                <nav class="topbar d-flex justify-content-between align-items-center">
                    <button id="sidebarToggle" class="btn text-secondary"><i class="bi bi-list fs-4"></i></button>
                </nav>

                <div class="container-fluid">
                    @include('partials.alerts')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Logika untuk toggle sidebar
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.getElementById('sidebar').classList.toggle('toggled');
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
