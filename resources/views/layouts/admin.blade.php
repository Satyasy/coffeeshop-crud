<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --sidebar-bg: #212529;
            /* Dark */
            --sidebar-text: rgba(255, 255, 255, 0.7);
            --sidebar-text-hover: #ffffff;
            --sidebar-active-bg: #c49b63;
            /* Warna aksen dari template */
            --content-bg: #f8f9fa;
            /* Light Gray */
            --card-border: #dee2e6;
            --card-bg: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--content-bg);
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex-grow: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
        }

        .content-header {
            margin-bottom: 2rem;
        }

        .card {
            border: 1px solid var(--card-border);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--card-border);
            font-weight: 600;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom-width: 2px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--sidebar-active-bg);
            border-color: var(--sidebar-active-bg);
        }

        .btn-primary:hover {
            background-color: #b38b52;
            border-color: #b38b52;
        }

        .alert {
            border-radius: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        @include('partials.sidebar')
        <main class="main-content">
            @include('partials.alerts')
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>

</html>
