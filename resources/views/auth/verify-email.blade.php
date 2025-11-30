<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - POS Office</title>

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
        .btn-link {
            color: var(--primary-color) !important;
            text-decoration: none;
        }
        .text-primary-color {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="auth-card p-4 bg-white">
        
        <div class="text-center mb-4">
            <i class="fa-solid fa-envelope-circle-check fa-3x text-primary-color mb-2"></i>
            <h5 class="fw-bold text-dark mb-0">Verifikasi Alamat Email</h5>
        </div>

        <div class="mb-4 small text-muted">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda belum menerima email, kami akan dengan senang hati mengirimkannya lagi.
        </div>

        <!-- Status Email Terkirim -->
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small">
                Tautan verifikasi baru telah dikirimkan ke alamat email Anda saat pendaftaran.
            </div>
        @endif

        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
            
            <!-- Form Kirim Ulang Tautan -->
            <form method="POST" action="{{ route('verification.send') }}" class="me-2">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    Kirim Ulang Tautan Verifikasi
                </button>
            </form>

            <!-- Form Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-muted">
                    Logout
                </button>
            </form>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>