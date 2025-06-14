@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Orders Management</h3>
            {{-- DIPERBAIKI: Menggunakan nama route 'admin.orders.create' --}}
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Create New Order</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total Price</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($order->status) }}</span></td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
