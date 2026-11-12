@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="fw-bold text-primary mb-0">Form Transaksi Penjualan</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold">Nama Pelanggan (Opsional)</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Nama pembeli...">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pilihan Status Transaksi -->
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status Pembayaran / Transaksi</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="completed">Completed (Lunas / Selesai)</option>
                            <option value="pending">Pending (Belum Lunas / Tertunda)</option>
                        </select>
                    </div>

                    <hr class="my-4">
                    <h5 class="fw-bold text-secondary mb-3">Pilih Produk Keluar</h5>

                    <!-- Struktur array items[0] agar sesuai dengan validasi controller -->
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Produk</label>
                            <select name="items[0][product_id]" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->selling_price, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Jumlah Jual (Qty)</label>
                            <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi Penjualan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
