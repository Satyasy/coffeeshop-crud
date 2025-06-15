@extends('layouts.app')

@section('title', 'Our Menu')

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

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 heading-section ftco-animate text-center">
                    <h2 class="mb-4">Pilih Menu Favoritmu</h2>
                    <p>Temukan beragam pilihan kopi, minuman non-kopi, dan makanan ringan yang kami siapkan khusus untuk Anda.</p>
                </div>
            </div>

            {{-- Navigasi Tab untuk Kategori --}}
            @if($categories->isNotEmpty())
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul class="nav nav-pills mb-5 ftco-animate" id="pills-tab" role="tablist">
                            {{-- Tombol untuk Semua Menu --}}
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" href="#pills-all" role="tab" aria-controls="pills-all" aria-selected="true">Semua Menu</a>
                            </li>
                            {{-- Tombol untuk setiap kategori dari database --}}
                            @foreach($categories as $category)
                                @if($category)
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-{{ Str::slug($category) }}-tab" data-bs-toggle="pill" href="#pills-{{ Str::slug($category) }}" role="tab" aria-controls="pills-{{ Str::slug($category) }}" aria-selected="false">{{ $category }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Konten Tab --}}
            <div class="tab-content" id="pills-tabContent">
                
                {{-- Tab untuk "Semua Menu" --}}
                <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                    <div class="row">
                        {{-- Loop semua item dari semua kategori --}}
                        @forelse($menus->flatten() as $menu)
                            @include('partials.menu_card', ['menu' => $menu])
                        @empty
                            <div class="col-12 text-center">
                                <p>Saat ini belum ada menu yang tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tab untuk setiap kategori --}}
                @foreach($categories as $category)
                    @if($category)
                        <div class="tab-pane fade" id="pills-{{ Str::slug($category) }}" role="tabpanel" aria-labelledby="pills-{{ Str::slug($category) }}-tab">
                            <div class="row">
                                @foreach($menus[$category] as $menu)
                                    @include('partials.menu_card', ['menu' => $menu])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </section>
@endsection