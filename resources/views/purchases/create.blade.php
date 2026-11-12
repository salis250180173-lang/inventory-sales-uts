@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="fw-bold text-primary mb-0">Form Transaksi Pembelian Stok</h4>
            </div>
            <div class="card-body">
                <!-- Menampilkan Error Validasi Laravel -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Menampilkan Error Sistem / Database -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf
                    
                    <!-- Pilih Supplier -->
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label fw-semibold">Pilih Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Pembelian -->
                    <div class="mb-3">
                        <label for="purchased_at" class="form-label fw-semibold">Tanggal Pembelian</label>
                        <input type="date" name="purchased_at" id="purchased_at" class="form-control @error('purchased_at') is-invalid @enderror" value="{{ old('purchased_at', date('Y-m-d')) }}" required>
                        @error('purchased_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Pembelian -->
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status Pembelian</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="completed" {{ old('status', 'completed') == 'completed' ? 'selected' : '' }}>Completed (Selesai / Stok Masuk)</option>
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending (Tertunda / Menunggu Barang)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h5 class="fw-bold text-secondary mb-3">Detail Barang Masuk</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Produk</label>
                            <select name="items[0][product_id]" class="form-select @error('items.0.product_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('items.0.product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jumlah Beli (Qty)</label>
                            <input type="number" name="items[0][quantity]" class="form-control @error('items.0.quantity') is-invalid @enderror" min="1" value="{{ old('items.0.quantity', 1) }}" required>
                            @error('items.0.quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Harga Beli Satuan</label>
                            <input type="number" name="items[0][unit_price]" class="form-control @error('items.0.unit_price') is-invalid @enderror" min="0" placeholder="0" value="{{ old('items.0.unit_price') }}" required>
                            @error('items.0.unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi Pembelian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
