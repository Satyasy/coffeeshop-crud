@extends('layouts.admin')

@section('title', 'Daftar Item Pesanan')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Item Pesanan</h1>
        <a href="{{ route('order_items.create', ['order_id' => request('order_id')]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Item Baru
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('order_items.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="order_id" class="form-label">Filter berdasarkan Pesanan (Order ID)</label>
                        <select name="order_id" id="order_id" class="form-select">
                            <option value="">Semua Pesanan</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->order_id }}" {{ request('order_id') == $order->order_id ? 'selected' : '' }}>
                                    #{{ $order->order_id }} - {{ $order->user->name ?? 'Tanpa Nama' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-filter me-1"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('order_items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Item Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Item</th>
                            <th>ID Pesanan</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga Saat Pesan</th>
                            <th>Subtotal</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderItems as $item)
                            <tr>
                                <td>{{ $item->order_item_id }}</td>
                                <td><a href="{{ route('orders.show', $item->order_id) }}">#{{ $item->order_id }}</a></td>
                                <td>{{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->quantity * $item->price_at_order, 0, ',', '.') }}</td>
                                <td>{{ Str::limit($item->notes, 30) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('order_items.edit', $item->order_item_id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Tombol Hapus (Membuka Modal) -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->order_item_id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Konfirmasi Hapus untuk setiap item -->
                            <div class="modal fade" id="deleteModal{{ $item->order_item_id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->order_item_id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $item->order_item_id }}">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus item **{{ $item->menu->name ?? 'ini' }}** dari pesanan **#{{ $item->order_id }}**?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            
                                            <!-- Form Hapus -->
                                            <form action="{{ route('order_items.destroy', $item->order_item_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data item pesanan tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $orderItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</div>
@endsection