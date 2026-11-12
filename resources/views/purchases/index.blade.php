@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header dengan Card Biru seperti Penjualan -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Riwayat Transaksi Pembelian (Stock-In)</h4>
            <a href="{{ route('purchases.create') }}" class="btn btn-light text-primary fw-semibold">
                + Tambah Pembelian Baru
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('purchases.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari No. PO..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100">Filter</button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Pembelian -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Petugas</th>
                            <th>Total Pembelian</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td class="fw-bold">{{ $purchase->po_number ?? $purchase->purchase_number ?? 'PO-' . $purchase->id }}</td>
                                <td>{{ $purchase->supplier->name ?? '-' }}</td>
                                <td>{{ $purchase->user->name ?? '-' }}</td>
                                <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($purchase->status == 'completed')
                                        <span class="badge bg-success px-3 py-2">COMPLETED</span>
                                    @elseif($purchase->status == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2">PENDING</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">CANCELLED</span>
                                    @endif
                                </td>
                                <td>{{ $purchase->created_at }}</td>
                                <td class="text-center">
                                    <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                                    
                                    @if(auth()->user()->role === 'owner' && $purchase->status !== 'cancelled')
                                        <form action="{{ route('purchases.cancel', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan transaksi pembelian ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat transaksi pembelian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $purchases->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
