@extends('layouts.app')

@section('title', 'Pilih Metode Pembayaran')

@section('content')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 ftco-animate">
                <h2 class="mb-4">Pembayaran untuk Pesanan #{{ $order->order_id }}</h2>

                @include('partials.alerts')

                <div class="billing-form ftco-bg-dark p-3 p-md-5">
                    <h3 class="mb-4 billing-heading">Ringkasan</h3>
                    <p class="d-flex">
                        <span>Total Tagihan</span>
                        <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </p>
                    <hr>

                    {{-- Form untuk memproses pembayaran --}}
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        {{-- Kirim data penting secara tersembunyi --}}
                        <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                        <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                        <input type="hidden" name="amount" value="{{ $order->total_price }}">
                        <input type="hidden" name="status" value="paid">
                        <input type="hidden" name="payment_time" value="{{ now() }}">
                        
                        <div class="form-group">
                            <label for="payment_method">Pilih Metode Pembayaran</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_gateway_reference">ID/Ref. Transaksi (Opsional)</label>
                            <input type="text" name="payment_gateway_reference" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary py-3 px-5">Bayar Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection