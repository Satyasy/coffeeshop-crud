@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="btn btn-primary shadow-sm"><i class="bi bi-download me-2"></i>Generate Report</a>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Pengguna</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalUsers ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-people-fill fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Penjualan</div>
                            <div class="h5 mb-0 fw-bold">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-cash-coin fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Sisa Stok</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalStock ?? 'N/A' }} Pcs</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-box-seam-fill fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pembayaran Sukses</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalPayments ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-check2-circle fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Grafik Pendapatan (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Komposisi Peran Pengguna</h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="rolesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Menu Paling Laris (Contoh)</h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="topMenusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Data Global untuk Grafik (Anda bisa ganti dengan data dari controller)
        const brandColor = '#c49b63';
        const brandColorRGB = '196, 155, 99';

        // ===================================
        // ### CHART 1: GRAFIK PENDAPATAN ###
        // ===================================
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: "Pendapatan",
                    lineTension: 0.3,
                    backgroundColor: `rgba(${brandColorRGB}, 0.1)`,
                    borderColor: brandColor,
                    pointBackgroundColor: brandColor,
                    data: [120000, 190000, 150000, 250000, 220000, 300000, 280000],
                }]
            },
            options: {
                maintainAspectRatio: false, // Penting! Agar grafik mengisi wadah div
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                        }
                    }
                }
            }
        });

        // ========================================
        // ### CHART 2: GRAFIK PERAN PENGGUNA ###
        // ========================================
        new Chart(document.getElementById('rolesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Customer', 'Admin', 'Cashier'],
                datasets: [{
                    data: [50, 5, 10],
                    backgroundColor: [brandColor, '#36a2eb', '#ff6384'],
                    hoverOffset: 4
                }]
            },
            options: {
                maintainAspectRatio: false, // Penting!
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // =====================================
        // ### CHART 3: GRAFIK MENU TERLARIS ###
        // =====================================
        new Chart(document.getElementById('topMenusChart'), {
            type: 'bar',
            data: {
                labels: ['Americano', 'Latte', 'Cappuccino', 'Red Velvet', 'Matcha'],
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: [65, 59, 80, 81, 56],
                    backgroundColor: `rgba(${brandColorRGB}, 0.8)`,
                    borderColor: `rgba(${brandColorRGB}, 1)`,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false, // Penting!
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
