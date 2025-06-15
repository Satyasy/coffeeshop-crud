<div class="col-md-6 col-lg-4 ftco-animate">
    <div class="menu-entry">
        <a href="#" class="img" style="background-image: url({{ $menu->image_url ? asset('storage/' . $menu->image_url) : asset('images/default-dish.jpg') }});"></a>
        <div class="text text-center pt-4">
            <h3><a href="#">{{ $menu->name }}</a></h3>
            <p>{{ Str::limit($menu->description, 50) }}</p>
            <p class="price"><span>Rp{{ number_format($menu->price, 0, ',', '.') }}</span></p>

            {{-- FORM UNTUK ADD TO CART --}}
            <form action="{{ route('cart.add', $menu->menu_id) }}" method="POST">
                @csrf
                <div class="input-group mb-3 justify-content-center">
                    {{-- Input kuantitas --}}
                    <input type="number" name="quantity" class="form-control text-center" value="1" min="1" style="max-width: 70px;">
                    <button class="btn btn-primary btn-outline-primary" type="submit">Add to Cart</button>
                </div>
            </form>
            
        </div>
    </div>
</div>