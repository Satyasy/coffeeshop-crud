@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pembayaran</h1>
        <a href="{{ route('payments.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Catat Pembayaran Baru
        </a>
    </div>

    {{-- Menampilkan pesan sukses/error dari session --}}
    @include('partials.alerts')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data Pembayaran</h6>
        </div>
        <div class="card-body">
            {{-- Form filter --}}
            <form action="{{ route('payments.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="order_id" class="form-label">Filter berdasarkan Pesanan</label>
                    <select name="order_id" id="order_id" class="form-select">
                        <option value="">Semua Pesanan</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->order_id }}" {{ request('order_id') == $order->order_id ? 'selected' : '' }}>
                                #{{ $order->order_id }} - {{ $order->user->name ?? 'Guest' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                     <label for="status" class="form-label">Filter berdasarkan Status</label>
                     <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Pembayaran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Bayar</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Waktu Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse ($payments as $payment)
        <tr>
            <td>{{ $payment->payment_id }}</td>
            <td>
                @if($payment->order)
                    <a href="{{ route('orders.show', $payment->order_id) }}">#{{ $payment->order_id }}</a>
                @else
                    #{{ $payment->order_id }}
                @endif
            </td>
            <td>{{ $payment->user->name ?? 'N/A' }}</td>
            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            <td>{{ $payment->payment_method }}</td>
            <td>
                {{-- MEMPERBAIKI TAMPILAN STATUS --}}
                @php
                    $statusClass = 'secondary';
                    if ($payment->status == \App\Models\Payment::STATUS_PENDING) {
                        $statusClass = 'warning';
                    } elseif ($payment->status == \App\Models\Payment::STATUS_PAID) {
                        $statusClass = 'success';
                    } elseif ($payment->status == \App\Models\Payment::STATUS_FAILED) {
                        $statusClass = 'danger';
                    }
                @endphp
                <span class="badge bg-{{ $statusClass }} text-dark">{{ $statuses[$payment->status] ?? ucfirst($payment->status) }}</span>
            </td>
            <td>
                {{-- MEMPERBAIKI TAMPILAN WAKTU BAYAR --}}
                {{ $payment->payment_time ? $payment->payment_time->format('d M Y, H:i') : '-' }}
            </td>
            <td>
                {{-- MEMPERBAIKI TAMPILAN TOMBOL AKSI --}}
                <div class="d-flex">
                    <a href="{{ route('payments.edit', $payment->payment_id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deletePaymentModal-{{ $payment->payment_id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                {{-- Modal Delete untuk setiap baris --}}
                <div class="modal fade" id="deletePaymentModal-{{ $payment->payment_id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">Apakah Anda yakin ingin menghapus data pembayaran untuk Order #{{ $payment->order_id }}?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ route('payments.destroy', $payment->payment_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada data pembayaran yang ditemukan.</td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
            
            {{-- Tampilkan pagination jika ada lebih dari 1 halaman --}}
            @if($payments->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $payments->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection