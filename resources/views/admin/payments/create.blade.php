@extends('layouts.admin')

@section('title', $selectedOrderId ? 'Catat Pembayaran Order #' . $selectedOrderId : 'Catat Pembayaran Baru')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $selectedOrderId ? 'Catat Pembayaran Order #' . $selectedOrderId : 'Catat Pembayaran Baru' }}</h1>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i> Kembali
        </a>
    </div>

    {{-- Ini akan menampilkan error validasi jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6 class="alert-heading">Ada beberapa error:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Pembayaran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="order_id" class="form-label">Order ID <span class="text-danger">*</span></label>
                        <select class="form-select @error('order_id') is-invalid @enderror" id="order_id" name="order_id" required>
                            <option value="">-- Pilih Order --</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->order_id }}" 
                                        data-amount="{{ $order->total_price }}"
                                        data-user-id="{{ $order->user_id }}" 
                                        {{ old('order_id', $selectedOrderId) == $order->order_id ? 'selected' : '' }}>
                                    #{{ $order->order_id }} - {{ $order->user->name ?? 'Guest' }} (Total: Rp {{ number_format($order->total_price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('order_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- DITAMBAHKAN: Dropdown untuk User, nilainya akan diisi oleh JavaScript --}}
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">-- Pilih User (Otomatis dari Order) --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}" {{ old('user_id') == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Jumlah Pembayaran (Rp) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required min="0">
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- DITAMBAHKAN: Dropdown untuk Metode Pembayaran --}}
                    <div class="col-md-6 mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="E-Wallet" {{ old('payment_method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', 'paid') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="payment_time" class="form-label">Waktu Pembayaran</label>
                        <input type="datetime-local" class="form-control @error('payment_time') is-invalid @enderror" id="payment_time" name="payment_time" value="{{ old('payment_time', now()->toDateTimeLocalString()) }}">
                        @error('payment_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="payment_gateway_reference" class="form-label">ID / Referensi Gateway (Opsional)</label>
                    <input type="text" class="form-control @error('payment_gateway_reference') is-invalid @enderror" id="payment_gateway_reference" name="payment_gateway_reference" value="{{ old('payment_gateway_reference') }}">
                    @error('payment_gateway_reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderSelect = document.getElementById('order_id');
        const amountInput = document.getElementById('amount');
        const userSelect = document.getElementById('user_id');

        function updateFields() {
            const selectedOption = orderSelect.options[orderSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                amountInput.value = '';
                userSelect.value = '';
                return;
            }
            
            const amount = selectedOption.getAttribute('data-amount');
            const userId = selectedOption.getAttribute('data-user-id');
            
            // Isi field amount jika belum diisi oleh old()
            if (amount && !amountInput.value) {
                amountInput.value = parseFloat(amount).toFixed(2);
            }
            
            // Pilih user yang sesuai di dropdown user
            if (userId) {
                userSelect.value = userId;
            }
        }

        // Jalankan saat halaman dimuat untuk menangani `old()` input
        updateFields();

        // Jalankan setiap kali pilihan order diubah
        orderSelect.addEventListener('change', updateFields);
    });
</script>
@endpush
@endsection