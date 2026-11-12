@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">Manajemen Data Produk</h5>
        <a href="{{ route('products.create') }}" class="btn btn-light btn-sm fw-bold text-primary">+ Tambah Produk</a>
    </div>
    <div class="card-body">
        <!-- Filter Pencarian -->
        <form action="{{ route('products.index') }}" method="GET">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari SKU / Nama Produk..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="low_stock" id="lowStock" value="1" {{ request('low_stock') ? 'checked' : '' }}>
                        <label class="form-check-label" for="lowStock">Stok Rendah Saja</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Filter</button>
                </div>
            </div>
        </form>

        <!-- Tabel Produk -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products ?? [] as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>Rp{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $product->stock <= $product->minimum_stock ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <!-- Tombol Hapus dengan Metode DELETE -->
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada data produk yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
