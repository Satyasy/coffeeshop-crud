@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
    <style>
        /* Styling khusus untuk kartu statistik di dashboard */
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
            font-size: 1.75rem;
            color: rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Pengguna</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalUsers ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto stat-card-icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Penjualan</div>
                            <div class="h5 mb-0 fw-bold">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto stat-card-icon"><i class="bi bi-cash-coin"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Sisa Stok</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalStock ?? 'N/A' }} Pcs</div>
                        </div>
                        <div class="col-auto stat-card-icon"><i class="bi bi-box-seam-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pembayaran Sukses</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalPayments ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto stat-card-icon"><i class="bi bi-check2-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
