@extends('layouts.admin')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Menu</h1>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Tambah Menu Baru
        </a>
    </div>

    @include('partials.alerts')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Menu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Kategori</th>
                            <th>Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{-- PERBAIKAN: Menggunakan accessor 'full_image_url' atau path 'image_url' --}}
                                    @if ($menu->image_url)
                                        <img src="{{ asset('storage/' . $menu->image_url) }}" alt="{{ $menu->name }}" width="80" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $menu->name }}</td>
                                <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td>{{ $menu->stock }}</td>
                                <td>{{ $menu->category ?? '-' }}</td>
                                <td>
                                    @if($menu->is_available)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-danger">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        {{-- PERBAIKAN: Memberikan ID menu ke route --}}
                                        <a href="{{ route('admin.menus.edit', $menu->menu_id) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteMenuModal-{{ $menu->menu_id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="deleteMenuModal-{{ $menu->menu_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus menu: <strong>{{ $menu->name }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    {{-- PERBAIKAN: Memberikan ID menu ke route --}}
                                                    <form action="{{ route('admin.menus.destroy', $menu->menu_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data menu tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Links --}}
            @if($menus->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection