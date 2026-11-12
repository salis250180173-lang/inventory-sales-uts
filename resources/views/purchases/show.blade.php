@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-white">Detail Transaksi Pembelian: {{ $purchase->purchase_number }}</h4>
            <a href="{{ route('purchases.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                Kembali
            </a>
        </div>
        <div class="card-body bg-white">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>No. PO:</strong> {{ $purchase->purchase_number }}</p>
                    <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? '-' }}</p>
                    <p><strong>Petugas:</strong> {{ $purchase->user->name ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Tanggal Pembelian:</strong> {{ $purchase->purchased_at }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-success text-uppercase">{{ $purchase->status }}</span>
                    </p>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Daftar Barang Masuk</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Jumlah (Qty)</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Keseluruhan:</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
