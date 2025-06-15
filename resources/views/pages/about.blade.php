@extends('layouts.app')

@section('content')
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url({{ asset('images/bg_3.jpg') }});" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">About Us</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="{{ url('/') }}">Home</a></span> <span>About</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-about d-md-flex">
        <div class="one-half img" style="background-image: url({{ asset('images/about.jpg') }});"></div>
        <div class="one-half ftco-animate">
            <div class="overlap">
                <div class="heading-section ftco-animate ">
                    <span class="subheading">Discover</span>
                    <h2 class="mb-4">Our Story</h2>
                </div>
                <div>
                    <p>On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country. But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section img" id="ftco-testimony" style="background-image: url({{ asset('images/bg_1.jpg') }});" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <span class="subheading">Testimony</span>
                <h2 class="mb-4">Customers Says</h2>
                <p>Apa kata pelanggan kami tentang kopi dan layanan yang kami berikan.</p>
            </div>
        </div>
    </div>
    <div class="container-wrap">
        {{-- Cek apakah ada data review --}}
        @if($reviews->isNotEmpty())
            <div class="row d-flex no-gutters">
                {{-- Loop untuk setiap review dari database --}}
                @foreach($reviews as $key => $review)
                    {{-- Memberi class 'overlay' pada testimoni yang genap untuk efek visual --}}
                    <div class="col-lg align-self-sm-end">
                        <div class="testimony {{ $key % 2 != 0 ? 'overlay' : '' }}">
                            <blockquote>
                                {{-- Tampilkan komentar dari database --}}
                                <p>“{{ $review->comment }}”</p>
                            </blockquote>
                            <div class="author d-flex mt-4">
                                <div class="image mr-3 align-self-center">
                                    {{-- Tampilkan gambar profil default atau dari user jika ada --}}
                                    <img src="{{ asset('images/person_'.($key % 3 + 1).'.jpg') }}" alt="Customer photo">
                                </div>
                                <div class="name align-self-center">
                                    {{-- Tampilkan nama user dari database --}}
                                    {{ $review->user->name ?? 'Anonymous' }}
                                    {{-- Menampilkan rating dalam bentuk bintang --}}
                                    <span class="position d-block">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $review->rating)
                                                <span class="icon-star"></span>
                                            @else
                                                <span class="icon-star-o"></span>
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Tampilkan pesan ini jika tidak ada review sama sekali --}}
            <div class="row justify-content-center">
                <div class="col-md-7 text-center text-white">
                    <p>Belum ada testimoni yang bisa ditampilkan.</p>
                </div>
            </div>
        @endif
    </div>
</section>

    <section class="ftco-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 pr-md-5">
                    <div class="heading-section text-md-right ftco-animate">
                        <span class="subheading">Discover</span>
                        <h2 class="mb-4">Our Menu</h2>
                        <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                        <p><a href="{{ url('/menu') }}" class="btn btn-primary btn-outline-primary px-4 py-3">View Full Menu</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="menu-entry">
                                <a href="#" class="img" style="background-image: url({{ asset('images/menu-1.jpg') }});"></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="menu-entry mt-lg-4">
                                <a href="#" class="img" style="background-image: url({{ asset('images/menu-2.jpg') }});"></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="menu-entry">
                                <a href="#" class="img" style="background-image: url({{ asset('images/menu-3.jpg') }});"></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="menu-entry mt-lg-4">
                                <a href="#" class="img" style="background-image: url({{ asset('images/menu-4.jpg') }});"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url({{ asset('images/bg_2.jpg') }});" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                    <strong class="number" data-number="100">0</strong>
                                    <span>Coffee Branches</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                    <strong class="number" data-number="85">0</strong>
                                    <span>Number of Awards</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                    <strong class="number" data-number="10567">0</strong>
                                    <span>Happy Customer</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                    <strong class="number" data-number="900">0</strong>
                                    <span>Staff</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
