@extends('layouts.admin')

@section('title', 'Show User')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $user->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Back to List</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">User Details</h5>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> <span
                    class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'success' }}">{{ ucfirst($user->role) }}</span>
            </p>
            <p><strong>Joined At:</strong> {{ $user->created_at->format('d M Y, H:i:s') }}</p>
            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y, H:i:s') }}</p>
        </div>
    </div>

    <h3 class="mt-4">User's Orders</h3>
    @if ($user->orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>This user has no orders.</p>
    @endif
@endsection
