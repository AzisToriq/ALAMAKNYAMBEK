<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - POS Office</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #435EBE;
            --bg-color: #F2F7FF;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-card {
            max-width: 420px;
            width: 90%;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            font-weight: 600;
            border-radius: 8px;
        }
        .text-primary-color {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-card p-4 bg-white">
        
        <div class="text-center mb-4">
            <i class="fa-solid fa-cash-register fa-3x text-primary-color mb-2"></i>
            <h5 class="fw-bold text-dark mb-0">POS & OFFICE SYSTEM</h5>
            <small class="text-muted">Akses ke Area Administrator</small>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success small">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label small fw-bold">Password</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label small" for="remember_me">Ingat Saya</label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-muted small text-decoration-none" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2">
                    LOGIN
                </button>
            </div>
        </form>
        
        <!-- TAMBAHAN: LINK KE REGISTRASI -->
        <div class="text-center mt-3 small">
            Belum punya akun?
            @if (Route::has('register'))
                <a class="text-primary-color text-decoration-none fw-bold" href="{{ route('register') }}">
                    Daftar Akun Baru
                </a>
            @endif
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>