@extends('layouts.admin')

@section('title', 'Menus Management')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Menus Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Create New Menu</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $menu)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" width="80"
                                class="img-thumbnail">
                        </td>
                        <td>{{ $menu->name }}</td>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td>{{ Str::limit($menu->description, 50) }}</td>
                        <td>{{ $menu->updated_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $menus->links() }}
@endsection
