@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary">Dashboard Utama</h2>
        <p class="text-muted">Selamat datang, {{ ucfirst(auth()->user()->role ?? 'Admin') }}! Berikut ringkasan sistem inventaris dan penjualan.</p>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title text-white-50">Total Produk</h6>
                <h3 class="fw-bold">{{ $totalProducts }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6 class="card-title text-white-50">Penjualan Hari Ini</h6>
                <h3 class="fw-bold">Rp{{ number_format($todaySales ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <h6 class="card-title text-white-50">Total Kategori</h6>
                <h3 class="fw-bold">{{ $totalCategories ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title text-dark">Total Transaksi</h6>
                <h3 class="fw-bold">{{ $totalTransactions }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Peringatan Stok Produk Rendah -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white py-3">
        <h5 class="mb-0 fw-bold">Peringatan Stok Produk Rendah (Low-Stock List)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Stok Saat Ini</th>
                        <th>Minimum Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td><span class="badge bg-danger">{{ $product->stock }}</span></td>
                            <td>{{ $product->minimum_stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Tidak ada produk dengan stok rendah saat ini. Semua stok aman!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
