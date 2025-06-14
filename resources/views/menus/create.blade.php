@extends('layouts.admin')

@section('title', 'Create Menu')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create New Menu</h1>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="price" class="form-label">Price (IDR)</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}"
                    required>
            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input class="form-control" type="file" id="image" name="image" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Menu</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
