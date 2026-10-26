<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris') - InventPro</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            /* Tema Pastel 'Coksu' (Beige / Cream / Soft Tan) Global - Rigid & Functional */
            --bg-canvas: #FAF7F2;
            --navbar-dark: #EBDCCB;
            --navbar-active: #FAF4EC;
            --text-dark: #2E2218;
            --text-muted: #735B44;
            --primary-brand: #4A392B;
            --primary-brand-hover: #382B20;
            --primary-terracotta: #4A392B;
            --primary-terracotta-hover: #382B20;
            --border-card: #e5e7eb;
            --header-table-bg: #EBDCCB;
            --card-bg: #FAF3EA;
        }

        body {
            background-color: var(--bg-canvas);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            font-size: 0.88rem;
        }

        /* Top Navbar Pastel Coksu (Rigid, border-gray-200) */
        .navbar-custom {
            background-color: var(--navbar-dark);
            padding-top: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand-badge {
            width: 32px;
            height: 32px;
            background: #DEC8B0;
            border: 1px solid #d1d5db;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4A392B;
            font-size: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .brand-title {
            font-weight: 700;
            font-size: 1rem;
            color: #2E2218;
            letter-spacing: -0.3px;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 0.70rem;
            color: #735B44;
            font-weight: 500;
            display: block;
        }

        .nav-custom-link {
            color: #5C4A3E !important;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 6px 12px !important;
            border-radius: 2px;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-custom-link:hover {
            color: #2E2218 !important;
            background-color: rgba(250, 244, 236, 0.8);
            border-color: #e5e7eb;
        }

        .nav-custom-link.active {
            background-color: var(--navbar-active) !important;
            color: #2E2218 !important;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        /* Dropdown Menu Pastel - Rigid */
        .dropdown-menu-dark-custom {
            background-color: #FAF3EA;
            border: 1px solid #e5e7eb;
            border-radius: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
            padding: 4px;
        }

        .dropdown-menu-dark-custom .dropdown-item {
            color: #4A392B;
            font-weight: 500;
            font-size: 0.82rem;
            border-radius: 2px;
            padding: 6px 10px;
            transition: all 0.15s ease;
        }

        .dropdown-menu-dark-custom .dropdown-item:hover,
        .dropdown-menu-dark-custom .dropdown-item.active {
            background-color: #EBDCCB;
            color: #2E2218;
            font-weight: 600;
        }

        /* Profile & User Badge in Navbar */
        .user-profile-box {
            line-height: 1.15;
            text-align: right;
        }

        .user-name-title {
            font-weight: 600;
            font-size: 0.82rem;
            color: #2E2218;
            display: block;
        }

        .user-handle {
            font-size: 0.72rem;
            color: #735B44;
        }

        .badge-role-pill {
            background-color: #FAF4EC;
            color: #4A392B;
            font-weight: 600;
            font-size: 0.74rem;
            padding: 4px 8px;
            border-radius: 2px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .badge-role-pill .badge {
            background-color: #9C8167 !important;
        }

        .btn-logout-pill {
            border: 1px solid #e5e7eb;
            color: #735B44;
            background: #ffffff;
            border-radius: 2px;
            padding: 4px 10px;
            font-weight: 500;
            font-size: 0.78rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease;
        }

        .btn-logout-pill:hover {
            border-color: #b91c1c;
            color: #ffffff;
            background-color: #b91c1c;
        }

        /* Rigid Card & Elements (Rounded-sm, border-gray-200, shadow-sm) */
        .card-custom,
        .card {
            background: var(--card-bg);
            border: 1px solid #e5e7eb !important;
            border-radius: 2px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }

        .card-header {
            background-color: #EBDCCB !important;
            border-bottom: 1px solid #e5e7eb !important;
            color: #2E2218 !important;
            border-top-left-radius: 2px !important;
            border-top-right-radius: 2px !important;
            font-weight: 700;
        }

        /* Tombol Utama Cokelat Susu (Rigid rounded-sm, shadow-sm) */
        .btn-terracotta,
        .btn-dark {
            background-color: #4A392B !important;
            color: #ffffff !important;
            border-radius: 2px !important;
            padding: 6px 14px !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            border: 1px solid #4A392B !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.15s ease !important;
        }

        .btn-terracotta:hover,
        .btn-dark:hover {
            background-color: #382B20 !important;
            border-color: #382B20 !important;
            color: #ffffff !important;
        }

        .btn-outline-terracotta {
            border: 1px solid #e5e7eb !important;
            color: #4A392B !important;
            background: #EBDCCB !important;
            border-radius: 2px !important;
            padding: 6px 14px !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.15s ease !important;
        }

        .btn-outline-terracotta:hover {
            background-color: #DEC8B0 !important;
            border-color: #d1d5db !important;
            color: #2E2218 !important;
        }

        .btn-outline-secondary {
            background-color: #FAF4EC !important;
            color: #5C4A3E !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 2px !important;
            font-size: 0.82rem !important;
            font-weight: 500 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.15s ease !important;
        }

        .btn-outline-secondary:hover {
            background-color: #EBDCCB !important;
            border-color: #d1d5db !important;
            color: #2E2218 !important;
        }

        .btn-primary {
            background-color: #4A392B !important;
            border-color: #4A392B !important;
            color: #ffffff !important;
            border-radius: 2px !important;
            font-weight: 600 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }

        .btn-primary:hover {
            background-color: #382B20 !important;
            border-color: #382B20 !important;
        }

        .btn-sm {
            border-radius: 2px !important;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: 2px !important;
            border-color: #e5e7eb !important;
            background-color: #FAF7F2;
            color: #2E2218;
            font-size: 0.84rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #C2A78C !important;
            background-color: #ffffff;
            color: #2E2218;
            box-shadow: 0 0 0 0.15rem rgba(194, 167, 140, 0.25);
        }

        .input-group-text {
            background-color: #FAF4EC;
            border-color: #e5e7eb !important;
            color: #735B44;
            border-radius: 2px !important;
        }

        /* Tabel & Header Tabel Pastel Coksu (Rigid) */
        .table {
            color: #2E2218;
            background-color: transparent;
        }

        .table-custom-header th {
            background-color: var(--header-table-bg) !important;
            color: #4A392B !important;
            font-size: 0.74rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 8px 12px;
            border-top: none;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .table > :not(caption) > * > * {
            padding: 8px 12px;
            vertical-align: middle;
            border-color: #e5e7eb !important;
            background-color: transparent;
        }

        .table-hover tbody tr:hover td {
            background-color: #FAF4EC !important;
        }

        /* Operational Standard Status Badges (Green for completed, Red for low stock/alert) */
        .badge.bg-success,
        .badge-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border: 1px solid #a7f3d0 !important;
            font-weight: 600;
            border-radius: 2px !important;
            font-size: 0.72rem;
            padding: 3px 6px;
        }

        .badge.bg-danger,
        .badge-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
            font-weight: 600;
            border-radius: 2px !important;
            font-size: 0.72rem;
            padding: 3px 6px;
        }

        .badge.bg-warning,
        .badge-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #fde68a !important;
            font-weight: 600;
            border-radius: 2px !important;
            font-size: 0.72rem;
            padding: 3px 6px;
        }

        .badge.bg-info,
        .badge-info,
        .badge.bg-primary {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            border: 1px solid #bfdbfe !important;
            font-weight: 600;
            border-radius: 2px !important;
            font-size: 0.72rem;
            padding: 3px 6px;
        }

        .badge.bg-secondary {
            background-color: #FAF4EC !important;
            color: #735B44 !important;
            border: 1px solid #e5e7eb !important;
            font-weight: 600;
            border-radius: 2px !important;
            font-size: 0.72rem;
            padding: 3px 6px;
        }

        /* Alerts */
        .alert-success {
            background-color: #d1fae5 !important;
            border-color: #a7f3d0 !important;
            color: #065f46 !important;
            border-radius: 2px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .alert-danger {
            background-color: #fee2e2 !important;
            border-color: #fecaca !important;
            color: #991b1b !important;
            border-radius: 2px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        /* Scrollable container */
        .custom-dashboard-scroll {
            max-height: 295px;
            overflow-y: auto;
        }

        .custom-dashboard-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-dashboard-scroll::-webkit-scrollbar-track {
            background: #FAF7F2;
        }

        .custom-dashboard-scroll::-webkit-scrollbar-thumb {
            background: #D7C3AD;
            border-radius: 2px;
        }

        .custom-dashboard-scroll::-webkit-scrollbar-thumb:hover {
            background: #C2A78C;
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-xl navbar-custom sticky-top">
        <div class="container-fluid px-3 px-xl-4">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center text-decoration-none me-4" href="{{ auth()->user()->isCustomer() ? route('customer.dashboard') : route('dashboard') }}">
                <div class="navbar-brand-badge me-2.5">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <span class="brand-title">InventPro</span>
                    <span class="brand-subtitle">Inventory &amp; Sales Cemilan &amp; Minuman</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation" style="color: #4A392B;">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 align-items-xl-center gap-1.5">
                    @if(auth()->user()->isCustomer())
                    <li class="nav-item">
                        <a class="nav-custom-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                            <i class="bi bi-shop me-2"></i> Katalog &amp; Belanja Saya
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-custom-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-house me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-custom-link dropdown-toggle {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'active' : '' }}" href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-folder2 me-2"></i> Master Data
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark-custom" aria-labelledby="masterDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                    <i class="bi bi-box me-2"></i> Produk &amp; Stok
                                </a>
                            </li>
                            @can('admin-only')
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                    <i class="bi bi-grid me-2"></i> Kategori Produk
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                                    <i class="bi bi-people me-2"></i> Pemasok (Supplier)
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-custom-link dropdown-toggle {{ request()->routeIs('purchases.*') || request()->routeIs('sales.*') ? 'active' : '' }}" href="#" id="transDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-arrow-left-right me-2"></i> Transaksi
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark-custom" aria-labelledby="transDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}">
                                    <i class="bi bi-box-arrow-in-right me-2 text-success"></i> Pembelian (Barang Masuk)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                                    <i class="bi bi-box-arrow-right me-2 text-primary"></i> Penjualan (Kasir)
                                </a>
                            </li>
                        </ul>
                    </li>
                    @can('admin-only')
                    <li class="nav-item">
                        <a class="nav-custom-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="bi bi-gear me-2"></i> Manajemen User
                        </a>
                    </li>
                    @endcan
                    @endif
                </ul>

                <!-- User Profile & Logout -->
                <div class="d-flex align-items-center gap-2.5 mt-3 mt-xl-0">
                    <div class="user-profile-box d-none d-sm-block">
                        <span class="user-name-title">{{ auth()->user()->name }}</span>
                        <span class="user-handle">{{ '@' . strtolower(auth()->user()->role) }}</span>
                    </div>

                    <div class="badge-role-pill">
                        <span class="badge rounded-circle p-1 me-1" style="width: 5px; height: 5px;"></span>
                        <span>{{ ucfirst(auth()->user()->role) }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout-pill d-flex align-items-center gap-1" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="py-4">
        <div class="container-fluid px-3 px-xl-4">
            <!-- Flash Alert Success -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show card-custom py-2.5 px-3 mb-3 small d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close position-static p-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Flash Alert Error -->
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show card-custom py-2.5 px-3 mb-3 small d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close position-static p-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- FOOTER PERSIS SESUAI INSTRUKSI USER -->
    <footer class="py-3 border-top mt-auto text-center" style="background-color: #FAF7F2; border-color: #e5e7eb !important; font-size: 0.78rem; color: #735B44;">
        <div class="container-fluid">
            &copy; 2026 - Naila Safarina - 250180118 - UTS Web Programming 2
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>