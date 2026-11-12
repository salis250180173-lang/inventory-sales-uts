@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Riwayat Transaksi Penjualan (Stock-Out)</h5>
            <a href="{{ route('sales.create') }}" class="btn btn-light btn-sm fw-bold text-primary">+ Buat Penjualan Baru</a>
        </div>
        <div class="card-body bg-white">
            <!-- Filter Bar -->
            <form action="{{ route('sales.index') }}" method="GET" class="row g-3 mb-4 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari No. Transaksi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark px-4 fw-semibold">Filter</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary px-4">Reset</a>
                </div>
            </form>

            <!-- Notifikasi Pesan Error / Sukses -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Peringatan!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabel Riwayat Penjualan -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Kasir / User</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Waktu Penjualan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="fw-semibold">{{ $sale->sale_number ?? $sale->transaction_number }}</td>
                                <td>{{ $sale->user->name ?? '-' }}</td>
                                <td>Rp {{ number_format($sale->total_amount ?? $sale->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($sale->status == 'completed')
                                        <span class="badge bg-success text-uppercase">Completed</span>
                                    @elseif($sale->status == 'pending')
                                        <span class="badge bg-warning text-dark text-uppercase">Pending</span>
                                    @else
                                        <span class="badge bg-danger text-uppercase">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $sale->sold_at ?? $sale->created_at }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                                        
                                        <!-- Tombol Cancel khusus Owner sesuai ketentuan role enforcement -->
                                        @if(auth()->user()->role === 'owner' && $sale->status !== 'cancelled')
                                            <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat transaksi penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $sales->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Riwayat Transaksi Penjualan (Stock-Out)</h5>
            <a href="{{ route('sales.create') }}" class="btn btn-light btn-sm fw-bold text-primary">+ Buat Penjualan Baru</a>
        </div>
        <div class="card-body bg-white">
            <!-- Filter Bar -->
            <form action="{{ route('sales.index') }}" method="GET" class="row g-3 mb-4 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari No. Transaksi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark px-4 fw-semibold">Filter</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary px-4">Reset</a>
                </div>
            </form>

            <!-- Notifikasi Pesan Error / Sukses -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Peringatan!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabel Riwayat Penjualan -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Kasir / User</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Waktu Penjualan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="fw-semibold">{{ $sale->sale_number ?? $sale->transaction_number }}</td>
                                <td>{{ $sale->user->name ?? '-' }}</td>
                                <td>Rp {{ number_format($sale->total_amount ?? $sale->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($sale->status == 'completed')
                                        <span class="badge bg-success text-uppercase">Completed</span>
                                    @elseif($sale->status == 'pending')
                                        <span class="badge bg-warning text-dark text-uppercase">Pending</span>
                                    @else
                                        <span class="badge bg-danger text-uppercase">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $sale->sold_at ?? $sale->created_at }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                                        
                                        <!-- Tombol Cancel khusus Owner sesuai ketentuan role enforcement -->
                                        @if(auth()->user()->role === 'owner' && $sale->status !== 'cancelled')
                                            <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat transaksi penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $sales->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
