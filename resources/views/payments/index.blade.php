@extends('layouts.admin')

@section('title', 'Payments Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Payments Management</h3>
            {{-- DIPERBAIKI: Menggunakan nama route 'admin.payments.create' --}}
            <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">Create New Payment</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td><a
                                        href="{{ route('admin.orders.show', $payment->order_id) }}">#{{ $payment->order_id }}</a>
                                </td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td><span class="badge badge-success">{{ ucfirst($payment->status) }}</span></td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('admin.payments.edit', $payment) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST"
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
                                <td colspan="7" class="text-center">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        </div>
    </div>
@endsection
