@extends('layouts.app')

@section('title', 'Our Menu') {{-- Opsional: Menambahkan judul halaman --}}

@section('content')

    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url({{ asset('images/bg_3.jpg') }});" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Our Menu</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Menu</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Anda bisa menghapus section ftco-intro jika tidak relevan dengan halaman menu --}}
    <section class="ftco-intro">
        {{-- ... (Konten intro statis Anda) ... --}}
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row">

                {{-- Memulai Loop Dinamis --}}
                @if($menus->isNotEmpty())
                    {{-- Loop untuk setiap KATEGORI (Main Dish, Drinks, dll.) --}}
                    @foreach($menus as $category => $items)
                        <div class="col-md-6 mb-5 pb-3">
                            <h3 class="mb-5 heading-pricing ftco-animate">{{ $category ?: 'Lainnya' }}</h3>
                            
                            {{-- Loop untuk setiap ITEM MENU di dalam kategori tersebut --}}
                            @foreach($items as $menu)
                                <div class="pricing-entry d-flex ftco-animate">
                                    {{-- Gunakan gambar dari database --}}
                                    <div class="img" style="background-image: url({{ $menu->image_url ? asset('storage/' . $menu->image_url) : asset('images/default-dish.jpg') }});"></div>
                                    <div class="desc pl-3">
                                        <div class="d-flex text align-items-center">
                                            {{-- Tampilkan nama dan harga dari database --}}
                                            <h3><span>{{ $menu->name }}</span></h3>
                                            <span class="price">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-block">
                                            {{-- Tampilkan deskripsi dari database --}}
                                            <p>{{ $menu->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>Saat ini belum ada menu yang tersedia. Silakan kembali lagi nanti.</p>
                    </div>
                @endif
                {{-- Akhir Loop Dinamis --}}

            </div>
        </div>
    </section>

    {{-- Anda bisa menghapus section ftco-menu jika bagian atas sudah cukup --}}
    {{-- Atau Anda bisa mengisinya juga dengan loop yang sama --}}
    <section class="ftco-menu mb-5 pb-5">
       {{-- ... (Konten statis ftco-menu Anda) ... --}}
    </section>

@endsection