<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS F&B') - Sistem Kasir</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E85A2A;
            --secondary: #004E89;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --sidebar-bg: linear-gradient(180deg, #1A1F36 0%, #0F1419 100%);
            --sidebar-width: 260px;
            --topbar-height: 70px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F8FAFC;
            color: #1E293B;
            overflow-x: hidden;
        }
        
        /* ========== SIDEBAR ========== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            z-index: 1050;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 107, 53, 0.1);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 8px 16px rgba(255, 107, 53, 0.3);
        }

        .brand-text h5 {
            color: white;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .brand-text small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            font-weight: 500;
        }

        /* Menu Sections */
        .sidebar-menu { padding: 16px 12px; }

        .menu-section {
            margin-bottom: 24px;
        }

        .menu-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 12px 8px;
            letter-spacing: 1px;
            margin-top: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 4px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }

        .sidebar-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            margin-right: 12px;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(255, 107, 53, 0.15) 0%, rgba(255, 107, 53, 0.05) 100%);
            color: white;
            font-weight: 600;
        }

        .sidebar-link.active::before {
            transform: scaleY(1);
        }

        .sidebar-link.active i {
            color: var(--primary);
        }

        /* Logout Button */
        .logout-section {
            padding: 16px;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            width: 100%;
            padding: 12px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1E293B;
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #64748B;
            font-weight: 400;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px 8px 8px;
            background: #F8FAFC;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-profile:hover {
            background: #F1F5F9;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1E293B;
            line-height: 1.2;
        }

        .user-role {
            font-size: 12px;
            color: #64748B;
        }

        /* Content Area */
        .content-area {
            padding: 32px;
        }

        /* Mobile Toggle */
        .btn-toggle-sidebar {
            display: none;
            width: 44px;
            height: 44px;
            background: white;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            color: #475569;
            font-size: 18px;
            transition: all 0.2s ease;
        }

        .btn-toggle-sidebar:hover {
            background: #F8FAFC;
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .btn-toggle-sidebar {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .top-navbar {
                padding: 0 16px;
            }

            .content-area {
                padding: 20px 16px;
            }

            .page-title {
                font-size: 20px;
            }

            .user-info {
                display: none;
            }
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ========== CARDS & COMPONENTS ========== */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            background: white;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Badge Styles */
        .badge-feature {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Quick Stats */
        .quick-stat {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(255, 107, 53, 0.05);
            border-radius: 10px;
            margin-top: 16px;
        }

        .quick-stat-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .quick-stat-text small {
            display: block;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }

        .quick-stat-text strong {
            font-size: 16px;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div class="brand-text">
                    <h5>F&B POS</h5>
                    <small>{{ Auth::user()->role == 'owner' ? 'Management Panel' : 'Cashier System' }}</small>
                </div>
            </div>
        </div>
        
        <!-- Menu -->
        <div class="sidebar-menu">
            
            @if(Auth::user()->role == 'owner')
            <!-- Main Menu -->
            <div class="menu-section">
                <div class="menu-label">📊 Overview</div>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Pengaturan Sistem</span>
                </a>
            </div>
            @endif

            <!-- Transaction Menu -->
            <div class="menu-section">
                <div class="menu-label">💰 Transaksi</div>
                <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Point of Sale</span>
                    <span class="badge-feature ms-auto">LIVE</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="sidebar-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </div>

            @if(Auth::user()->role == 'owner')
            <!-- Menu Management -->
            <div class="menu-section">
                <div class="menu-label">🍽️ Menu Management</div>
                <a href="{{ route('master.index') }}" class="sidebar-link {{ request()->routeIs('master.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-tree"></i>
                    <span>Kategori & Satuan</span>
                </a>
                <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bowl-food"></i>
                    <span>Produk Menu</span>
                </a>
                <a href="{{ route('modifiers.index') }}" class="sidebar-link {{ request()->routeIs('modifiers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-puzzle-piece"></i>
                    <span>Add-ons / Modifier</span>
                </a>
            </div>

            <!-- Inventory (if enabled) -->
            @if(isset($setting) && $setting && $setting->enable_supplier)
            <div class="menu-section">
                <div class="menu-label">📦 Supply Chain</div>
                <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-field"></i>
                    <span>Supplier</span>
                </a>
            </div>
            @endif

            @if(isset($setting) && $setting && $setting->enable_inventory)
            <div class="menu-section">
                <div class="menu-label">📊 Inventory</div>
                <a href="{{ route('inventory.index') }}" class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Stok Masuk/Keluar</span>
                </a>
            </div>
            @endif

            <!-- Finance (if enabled) -->
            @if(isset($setting) && $setting && $setting->enable_finance)
            <div class="menu-section">
                <div class="menu-label">💵 Keuangan</div>
                <a href="{{ route('expenses.index') }}" class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Biaya Operasional</span>
                </a>
                <a href="{{ route('reports.profit_loss') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Laporan Laba Rugi</span>
                </a>
            </div>
            @endif

            <!-- User Management -->
            <div class="menu-section">
                <div class="menu-label">👥 Users</div>
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Manajemen Pengguna</span>
                </a>
            </div>
            @endif

        </div>

        <!-- Logout -->
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-toggle-sidebar" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <div class="page-title">@yield('title', 'Dashboard')</div>
                    <div class="page-subtitle d-none d-md-block">@yield('subtitle', 'Selamat datang di sistem POS F&B')</div>
                </div>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const btnToggle = document.getElementById('sidebarToggle');

        btnToggle?.addEventListener('click', () => {
            sidebar.classList.add('show');
            overlay.classList.add('show');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Auto close sidebar on menu click (mobile)
        if (window.innerWidth <= 991) {
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>