<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'POS F&B System') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F8FAFC;
            color: #1E293B;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: white !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 16px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 20px;
            background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1A1F36 0%, #0F1419 100%);
            padding: 120px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 900;
            color: white;
            line-height: 1.2;
            margin-bottom: 24px;
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto 40px;
        }

        .btn-hero {
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 12px;
            background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%);
            border: none;
            color: white;
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.3);
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(255, 107, 53, 0.15);
            border: 1px solid rgba(255, 107, 53, 0.3);
            border-radius: 50px;
            color: #FCA5A5;
            font-size: 14px;
            font-weight: 600;
            margin-top: 24px;
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: white;
        }

        .section-title {
            font-size: 42px;
            font-weight: 800;
            color: #1E293B;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            font-size: 18px;
            color: #64748B;
            margin-bottom: 64px;
        }

        .feature-card {
            background: white;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 40px 32px;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #FF6B35 0%, #E85A2A 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: #FF6B35;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
        }

        .feature-icon.orange {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(232, 90, 42, 0.1) 100%);
            color: #FF6B35;
        }

        .feature-icon.green {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            color: #10B981;
        }

        .feature-icon.blue {
            background: linear-gradient(135deg, rgba(0, 78, 137, 0.1) 0%, rgba(0, 58, 99, 0.1) 100%);
            color: #004E89;
        }

        .feature-title {
            font-size: 22px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 12px;
        }

        .feature-description {
            font-size: 15px;
            color: #64748B;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%);
            color: white;
        }

        .stat-item {
            text-align: center;
            padding: 24px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: white;
        }

        .cta-card {
            background: linear-gradient(135deg, #1A1F36 0%, #0F1419 100%);
            border-radius: 24px;
            padding: 64px 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
        }

        .cta-title {
            font-size: 38px;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .cta-description {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 32px;
            position: relative;
            z-index: 1;
        }

        /* Footer */
        footer {
            background: #1A1F36;
            color: rgba(255, 255, 255, 0.6);
            padding: 48px 0 24px;
        }

        .footer-brand {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 36px;
            }

            .hero-subtitle {
                font-size: 16px;
            }

            .section-title {
                font-size: 32px;
            }

            .stat-number {
                font-size: 36px;
            }

            .cta-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa-solid fa-utensils me-2"></i>
                F&B POS SYSTEM
            </a>
            <div class="ms-auto d-flex gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-dark px-4">
                        <i class="fa-solid fa-gauge-high me-1"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%); color: white; border: none;">
                        <i class="fa-solid fa-lock me-1"></i>
                        Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">
                    Sistem Kasir Modern<br>
                    Untuk Bisnis F&B Anda
                </h1>
                <p class="hero-subtitle">
                    Kelola restoran, cafe, atau warung makan dengan sistem POS yang powerful, 
                    mudah digunakan, dan dilengkapi laporan laba rugi real-time.
                </p>
                <a href="{{ route('login') }}" class="btn btn-hero">
                    <i class="fa-solid fa-rocket me-2"></i>
                    Mulai Sekarang
                </a>
                <div class="hero-badge">
                    <i class="fa-solid fa-star me-1"></i>
                    Cocok untuk UMKM & Enterprise
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fa-solid fa-bolt"></i>
                            Fast
                        </div>
                        <div class="stat-label">Transaksi Cepat</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fa-solid fa-mobile-screen"></i>
                            Mobile
                        </div>
                        <div class="stat-label">Responsive Design</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fa-solid fa-shield-halved"></i>
                            Secure
                        </div>
                        <div class="stat-label">Data Aman</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fa-solid fa-chart-line"></i>
                            Smart
                        </div>
                        <div class="stat-label">Analisis Real-time</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Fitur Unggulan</h2>
                <p class="section-subtitle">Semua yang Anda butuhkan untuk mengelola bisnis F&B modern</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <h3 class="feature-title">Laporan Laba Rugi Otomatis</h3>
                        <p class="feature-description">
                            Hitung profit bersih secara otomatis dengan detail HPP, omzet, dan biaya operasional. 
                            Tidak perlu ribet hitung manual lagi.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="fa-solid fa-cash-register"></i>
                        </div>
                        <h3 class="feature-title">POS Modern & Responsif</h3>
                        <p class="feature-description">
                            Antarmuka kasir yang cepat dan mudah digunakan. Support QRIS, nomor meja, 
                            add-ons menu, dan catatan pesanan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <h3 class="feature-title">Sistem Fleksibel</h3>
                        <p class="feature-description">
                            Aktifkan hanya fitur yang Anda butuhkan. Pajak, supplier, inventory, dan 
                            modul lainnya bisa disesuaikan dengan bisnis Anda.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="fa-solid fa-puzzle-piece"></i>
                        </div>
                        <h3 class="feature-title">Modifier & Add-ons</h3>
                        <p class="feature-description">
                            Kelola varian menu dengan mudah. Extra topping, level pedas, ukuran porsi, 
                            semua bisa dikustomisasi sesuai kebutuhan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Inventory</h3>
                        <p class="feature-description">
                            Monitor stok bahan baku secara real-time. Alert otomatis saat stok menipis, 
                            lengkap dengan riwayat stok masuk/keluar.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        <h3 class="feature-title">Cetak Struk Thermal</h3>
                        <p class="feature-description">
                            Cetak struk langsung ke printer thermal 80mm. Format professional dengan 
                            detail lengkap termasuk add-ons dan catatan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <h2 class="cta-title">Siap Tingkatkan Bisnis F&B Anda?</h2>
                <p class="cta-description">Mulai gunakan sistem kasir modern yang akan memudahkan operasional bisnis Anda.</p>
                <a href="{{ route('login') }}" class="btn btn-hero">
                    <i class="fa-solid fa-arrow-right me-2"></i>
                    Akses Admin Panel
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="footer-brand">
                        <i class="fa-solid fa-utensils me-2"></i>
                        F&B POS
                    </div>
                    <p class="mb-0">Sistem Point of Sale modern untuk bisnis Food & Beverage.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-2">
                        <i class="fa-solid fa-code me-2"></i>
                        Developed by IT Informatic UNIMUS
                    </p>
                    <p class="mb-0">
                        &copy; {{ date('Y') }} All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>