<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Password - POS Office</title>

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
        .auth-card {
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
    <div class="auth-card p-4 bg-white">
        
        <div class="text-center mb-4">
            <i class="fa-solid fa-shield-halved fa-3x text-primary-color mb-2"></i>
            <h5 class="fw-bold text-dark mb-0">Area Aman</h5>
        </div>

        <div class="mb-4 small text-muted text-center">
            Ini adalah area aman aplikasi. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
        </div>

        @if ($errors->any())
            <div class="alert alert-danger small">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label small fw-bold">Kata Sandi</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" autofocus>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2">
                    KONFIRMASI
                </button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <a class="text-muted small text-decoration-none" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-times me-1"></i> Kembali ke Dashboard
            </a>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>