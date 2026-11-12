<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory & Sales Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar Atas (Posisinya di paling atas) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3 mb-4 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">INV-SALES UTS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-warning fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('products.index') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('categories.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('suppliers.index') }}">Supplier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('purchases.index') }}">Pembelian (Stock-In)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sales.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('sales.index') }}">Penjualan (Stock-Out)</a>
                    </li>
                </ul>

                <!-- Bagian Kanan: Tiga Tombol Role (OWNER, USER, ADMIN) -->
                <div class="d-flex align-items-center text-white gap-3">
                    @php
                        // Deteksi role dari user yang sedang login atau session
                        $user = auth()->user();
                        $role = strtoupper($user->role ?? session('user_role', 'ADMIN'));
                    @endphp

                    <div class="d-flex align-items-center gap-1 bg-secondary bg-opacity-75 p-1 rounded border border-secondary">
                        <span class="badge {{ $role == 'OWNER' ? 'bg-warning text-dark' : 'bg-secondary text-white opacity-50' }} fw-bold px-2 py-1">OWNER</span>
                        <span class="badge {{ $role == 'USER' || $role == 'KASIR' ? 'bg-success text-white' : 'bg-secondary text-white opacity-50' }} fw-bold px-2 py-1">USER</span>
                        <span class="badge {{ $role == 'ADMIN' ? 'bg-info text-dark' : 'bg-secondary text-white opacity-50' }} fw-bold px-2 py-1">ADMIN</span>
                    </div>

                    <!-- Tombol Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 fw-bold">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Halaman -->
    <div class="container mb-5">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
