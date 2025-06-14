@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Menu: {{ $menu->name }}</h1>
    </div>

    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $menu->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="price" class="form-label">Price (IDR)</label>
                <input type="number" class="form-control" id="price" name="price"
                    value="{{ old('price', $menu->price) }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $menu->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image (Leave blank to keep existing)</label>
            <div class="mb-2">
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" width="150"
                    class="img-thumbnail">
            </div>
            <input class="form-control" type="file" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Update Menu</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
