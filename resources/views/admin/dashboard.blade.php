@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="content-header">
        <h1 class="h2 font-weight-bold">Dashboard</h1>
        <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card h-100">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted font-weight-normal mb-1">Total Pengguna Aktif</h6>
                            <h3 class="font-weight-bold mb-0">{{ $totalUsers }}</h3>
                        </div>
                        <div class="stat-card-icon icon-users">
                            <span class="oi oi-people"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card h-100">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted font-weight-normal mb-1">Total Penjualan</h6>
                            <h3 class="font-weight-bold mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                        </div>
                        <div class="stat-card-icon icon-sales">
                            <span class="oi oi-dollar"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card h-100">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted font-weight-normal mb-1">Sisa Stok Menu</h6>
                            <h3 class="font-weight-bold mb-0">{{ $totalStock }} Pcs</h3>
                        </div>
                        <div class="stat-card-icon icon-stock">
                            <span class="oi oi-inbox"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card h-100">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted font-weight-normal mb-1">Pembayaran Sukses</h6>
                            <h3 class="font-weight-bold mb-0">{{ $totalPayments }}</h3>
                        </div>
                        <div class="stat-card-icon icon-payments">
                            <span class="oi oi-task"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Aktivitas Terbaru
                </div>
                <div class="card-body">
                    <p class="text-center text-muted">Grafik penjualan atau daftar pesanan terbaru akan muncul di sini.</p>
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- Tambahkan CSS ini ke layout admin Anda atau di sini --}}
@push('styles')
    <style>
        .stat-card {
            background-color: #fff;
            border: 1px solid var(--card-border);
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .stat-card-body {
            padding: 1.5rem;
        }

        .stat-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            color: #fff;
        }

        .icon-users {
            background-color: #17a2b8;
        }

        /* Info */
        .icon-sales {
            background-color: #28a745;
        }

        /* Success */
        .icon-stock {
            background-color: #ffc107;
        }

        /* Warning */
        .icon-payments {
            background-color: #007bff;
        }

        /* Primary */
    </style>
@endpush

{{-- Jika layout Anda belum punya @stack('styles'), tambahkan ini di <head> layout admin: --}}
{{-- @stack('styles') --}}
jdo
