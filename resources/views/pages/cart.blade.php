@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <h2 class="mb-4">Keranjang Belanja Saya</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(count($cartItems) > 0)
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th> </th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $id => $details)
                                    <tr class="text-center">
                                        <td class="image-prod"><div class="img" style="background-image:url({{ $details['image_url'] ? asset('storage/' . $details['image_url']) : asset('images/default-dish.jpg') }});"></div></td>
                                        <td class="product-name">
                                            <h3>{{ $details['name'] }}</h3>
                                        </td>
                                        <td class="price">Rp{{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td class="quantity">
                                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group">
                                                    <input type="number" name="quantity" class="form-control text-center" value="{{ $details['quantity'] }}" min="1">
                                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="total">Rp{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td class="product-remove">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">X</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row justify-content-end">
                            <div class="col-lg-4 mt-5 cart-wrap ftco-animate">
                                <div class="cart-total mb-3">
                                    <h3>Total Keranjang</h3>
                                    <hr>
                                    <p class="d-flex total-price">
                                        <span>Total</span>
                                        <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                                <p><a href="{{ route('checkout') }}" class="btn btn-primary py-3 px-4">Lanjutkan ke Checkout</a></p>
                                <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-outline-danger">Kosongkan Keranjang</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">Keranjang belanja Anda masih kosong.</div>
                        <p><a href="{{ route('menu') }}" class="btn btn-primary">Lihat Menu</a></p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection