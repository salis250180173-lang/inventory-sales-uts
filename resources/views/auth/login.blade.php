<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - INV-SALES UTS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons untuk Tombol Mata -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg border-0 p-4" style="width: 440px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">INV-SALES UTS</h3>
            <p class="text-muted small">Silakan login menggunakan Email & Password</p>
        </div>

        <!-- Notifikasi Error jika Gagal Login -->
        @if(session('error'))
            <div class="alert alert-danger py-2 small" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @error('email')
            <div class="alert alert-danger py-2 small" role="alert">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small">Email / Username</label>
                <input type="email" name="email" class="form-control" placeholder="admin@inventory.test" required value="{{ old('email', 'admin@inventory.test') }}">
            </div>

            <!-- Input Password dengan Tombol Mata -->
            <div class="mb-3">
                <label class="form-label fw-bold small">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required value="password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3 form-check">
                <!-- Tambahan name="remember" agar terbaca di AuthController -->
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small" for="remember">Ingat Saya</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Masuk (Login)</button>
        </form>
        
        <!-- Keterangan Akun 3 Role -->
        <div class="mt-4 pt-3 border-top text-start">
            <p class="fw-bold small mb-2 text-dark">Keterangan Akun Login (Password: <span class="text-danger">password</span>):</p>
            <ul class="small text-muted ps-3 mb-0" style="font-size: 12px;">
                <li><strong>Owner:</strong> owner@inventory.test</li>
                <li><strong>Admin:</strong> admin@inventory.test</li>
                <li><strong>User / Kasir:</strong> user@inventory.test</li>
            </ul>
        </div>
    </div>

    <!-- Script JavaScript untuk Toggle Tombol Mata -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
