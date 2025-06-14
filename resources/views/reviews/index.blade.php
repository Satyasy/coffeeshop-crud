@extends('layouts.admin')

@section('title', 'Reviews Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Reviews Management</h3>
            {{-- DIPERBAIKI: Menggunakan nama route 'admin.reviews.create' --}}
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">Create New Review</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Menu</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>#{{ $review->id }}</td>
                                <td>{{ $review->user->name }}</td>
                                <td>{{ $review->menu->name }}</td>
                                <td>{{ $review->rating }} ★</td>
                                <td>{{ Str::limit($review->comment, 50) }}</td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('admin.reviews.edit', $review) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
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
                                <td colspan="7" class="text-center">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
