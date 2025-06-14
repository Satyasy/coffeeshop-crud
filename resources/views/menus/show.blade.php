@extends('layouts.admin')

@section('title', 'Show Menu')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $menu->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary ml-2">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid rounded" alt="{{ $menu->name }}">
        </div>
        <div class="col-md-8">
            <h3>Details</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>ID:</strong> {{ $menu->id }}</li>
                <li class="list-group-item"><strong>Name:</strong> {{ $menu->name }}</li>
                <li class="list-group-item"><strong>Price:</strong> Rp {{ number_format($menu->price, 0, ',', '.') }}</li>
                <li class="list-group-item"><strong>Description:</strong> {{ $menu->description }}</li>
                <li class="list-group-item"><strong>Created At:</strong> {{ $menu->created_at->format('d M Y H:i') }}</li>
                <li class="list-group-item"><strong>Updated At:</strong> {{ $menu->updated_at->format('d M Y H:i') }}</li>
            </ul>
        </div>
    </div>
@endsection
