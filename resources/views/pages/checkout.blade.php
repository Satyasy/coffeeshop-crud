@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <section class="home-slider owl-carousel">
        {{-- ... (Bagian slider sama seperti sebelumnya) ... --}}
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-xl-12 ftco-animate"> {{-- DIBUAT FULL WIDTH --}}
            
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

			<form action="{{ route('checkout.place') }}" method="POST" class="billing-form ftco-bg-dark p-3 p-md-5">
                @csrf
                <div class="row">

                    {{-- KOLOM KIRI: DETAIL PELANGGAN --}}
                    <div class="col-md-7">
                        <h3 class="mb-4 billing-heading">Detail Pelanggan</h3>
                        <div class="row align-items-end">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Alamat (Opsional, untuk referensi)</label>
                                    <textarea name="delivery_address" class="form-control" placeholder="Masukkan alamat Anda di sini">{{ Auth::user()->address }}</textarea>
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Catatan untuk Pesanan (Opsional)</label>
                                    <textarea name="notes_for_restaurant" class="form-control" placeholder="Contoh: Tidak pakai bawang, ekstra pedas."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: RINGKASAN & TOMBOL SUBMIT --}}
                    <div class="col-md-5">
                        <div class="cart-detail p-3 p-md-4">
                            <h3 class="billing-heading mb-4">Ringkasan Pesanan</h3>
                            
                            @foreach($cartItems as $item)
                                <p class="d-flex">
                                    <span>{{ $item['name'] }} <small>x{{ $item['quantity'] }}</small></span>
                                    <span>Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </p>
                            @endforeach
                            
                            <hr>
                            <p class="d-flex total-price">
                                <span>Total</span>
                                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        
                        {{-- PERBAIKAN: Tombol submit sekarang ada DI DALAM form --}}
                        <div class="cart-detail p-3 p-md-4">
                            <p class="text-center">
                                <button type="submit" class="btn btn-primary py-3 px-4 w-100">Lanjutkan ke Pembayaran</button>
                            </p>
                        </div>
                    </div>

                </div>
	        </form><!-- END -->
          </div>
        </div>
      </div>
    </section>
@endsection

{{-- SCRIPT DI BAWAH TIDAK DIPERLUKAN LAGI --}}
{{-- 
@push('scripts')
<script>
    // Menambahkan ID pada form agar bisa di-submit dari luar form
    // document.querySelector('.billing-form').id = 'checkoutForm';
</script>
@endpush 
--}}