@extends('layouts.admin')

@section('title', 'Order Items Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Order Items Management</h3>
            {{-- DIPERBAIKI: Menggunakan nama route 'admin.order-items.create' --}}
            <a href="{{ route('admin.order-items.create') }}" class="btn btn-primary">Create New Item</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order ID</th>
                            <th>Menu Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderItems as $item)
                            <tr>
                                <td>#{{ $item->id }}</td>
                                <td><a href="{{ route('admin.orders.show', $item->order_id) }}">#{{ $item->order_id }}</a>
                                </td>
                                <td>{{ $item->menu->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.order-items.show', $item) }}"
                                        class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('admin.order-items.edit', $item) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.order-items.destroy', $item) }}" method="POST"
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
                                <td colspan="6" class="text-center">No order items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orderItems->links() }}
        </div>
    </div>
@endsection
