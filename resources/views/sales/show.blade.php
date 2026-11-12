@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-white">Detail Transaksi Penjualan: {{ $sale->sale_number ?? $sale->transaction_number ?? 'TRX-' . $sale->id }}</h4>
            <a href="{{ route('sales.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                Kembali
            </a>
        </div>
        <div class="card-body bg-white">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>No. Transaksi:</strong> {{ $sale->sale_number ?? $sale->transaction_number ?? 'TRX-' . $sale->id }}</p>
                    <p><strong>Kasir / User:</strong> {{ $sale->user->name ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Waktu Penjualan:</strong> {{ $sale->sold_at ?? $sale->created_at }}</p>
                    <p><strong>Status:</strong> 
                        @if($sale->status == 'completed')
                            <span class="badge bg-success text-uppercase">Completed</span>
                        @elseif($sale->status == 'pending')
                            <span class="badge bg-warning text-dark text-uppercase">Pending</span>
                        @else
                            <span class="badge bg-danger text-uppercase">Cancelled</span>
                        @endif
                    </p>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Daftar Produk Keluar</h5>
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
                        @foreach($sale->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Keseluruhan:</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($sale->total_amount ?? $sale->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
