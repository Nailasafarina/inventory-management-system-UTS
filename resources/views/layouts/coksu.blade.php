<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Inventory & Sales</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        coksu: {
                            50: '#FAF7F2',   // background canvas (warm cream)
                            100: '#FAF4EC',  // active sidebar / subtle highlight
                            150: '#FAF3EA',  // card background
                            200: '#EBDCCB',  // sidebar bg & header tables
                            250: '#E2D1BD',  // subtle borders
                            300: '#D7C3AD',  // dividers
                            400: '#DEC8B0',  // warning/alert card bg
                            500: '#C2A78C',  // warm tan accent
                            600: '#9C8167',  // secondary text / icons
                            700: '#735B44',  // dark warm brown
                            800: '#4A392B',  // primary dark text / buttons
                            900: '#2E2218',  // deep espresso text
                        }
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #FAF7F2;
        }
        ::-webkit-scrollbar-thumb {
            background: #D7C3AD;
            border-radius: 2px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #C2A78C;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-[#FAF7F2] text-[#2E2218] font-sans antialiased selection:bg-[#DEC8B0] selection:text-[#2E2218]">

    <div class="min-h-full flex flex-col md:flex-row relative">

        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-[#2E2218]/30 backdrop-blur-xs z-40 hidden transition-opacity"></div>

        <!-- SIDEBAR (Rigid & Functional, Rounded-sm, Border-gray-200) -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#EBDCCB] border-r border-gray-200 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static md:h-screen md:sticky md:top-0">
            <div>
                <!-- Brand / Logo -->
                <div class="h-16 flex items-center justify-between px-5 border-b border-gray-200 bg-[#E2D1BD]/40">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 text-[#2E2218] font-bold text-base tracking-tight group">
                        <div class="w-8 h-8 rounded-sm bg-[#DEC8B0] border border-gray-300 flex items-center justify-center text-[#4A392B] shadow-sm">
                            <i class="bi bi-shop text-sm"></i>
                        </div>
                        <span class="text-[#2E2218] font-bold text-sm tracking-wide">Inventory &amp; Sales</span>
                    </a>
                    <!-- Close button on mobile -->
                    <button id="close-sidebar-btn" class="md:hidden text-[#4A392B] hover:text-[#2E2218] p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <!-- Navigation Links (Rounded-sm, Functional ERP style) -->
                <nav class="px-3 py-3 space-y-1">
                    <!-- Dashboard (Active Pill) -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-semibold transition border border-gray-200 {{ request()->routeIs('dashboard') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-house text-sm"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Kategori -->
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium transition border border-gray-200 {{ request()->routeIs('categories.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-grid text-sm"></i>
                        <span>Kategori</span>
                    </a>
                    @else
                    <a href="#" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium text-[#4A392B] bg-[#FAF3EA]/40 border border-gray-200 hover:text-[#2E2218] hover:bg-[#FAF4EC] transition">
                        <i class="bi bi-grid text-sm"></i>
                        <span>Kategori</span>
                    </a>
                    @endif

                    <!-- Produk -->
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium transition border border-gray-200 {{ request()->routeIs('products.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-box text-sm"></i>
                        <span>Produk</span>
                    </a>

                    <!-- Pemasok -->
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('suppliers.index') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium transition border border-gray-200 {{ request()->routeIs('suppliers.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-people text-sm"></i>
                        <span>Pemasok</span>
                    </a>
                    @else
                    <a href="#" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium text-[#4A392B] bg-[#FAF3EA]/40 border border-gray-200 hover:text-[#2E2218] hover:bg-[#FAF4EC] transition">
                        <i class="bi bi-people text-sm"></i>
                        <span>Pemasok</span>
                    </a>
                    @endif

                    <!-- Transaksi Masuk -->
                    <a href="{{ route('purchases.index') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium transition border border-gray-200 {{ request()->routeIs('purchases.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-box-arrow-in-right text-sm"></i>
                        <span>Transaksi Masuk</span>
                    </a>

                    <!-- Transaksi Keluar -->
                    <a href="{{ route('sales.index') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium transition border border-gray-200 {{ request()->routeIs('sales.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-box-arrow-right text-sm"></i>
                        <span>Transaksi Keluar</span>
                    </a>

                    <!-- Pengaturan -->
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium transition border border-gray-200 {{ request()->routeIs('users.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#4A392B] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }}">
                        <i class="bi bi-gear text-sm"></i>
                        <span>Pengaturan</span>
                    </a>
                    @else
                    <a href="#" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-sm text-xs font-medium text-[#4A392B] bg-[#FAF3EA]/40 border border-gray-200 hover:text-[#2E2218] hover:bg-[#FAF4EC] transition">
                        <i class="bi bi-gear text-sm"></i>
                        <span>Pengaturan</span>
                    </a>
                    @endif
                </nav>
            </div>

            <!-- Sidebar User Info & Logout (Rounded-sm) -->
            <div class="p-3 border-t border-gray-200 bg-[#E2D1BD]/30">
                <div class="flex items-center justify-between px-3 py-2 rounded-sm bg-white border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <div class="w-6 h-6 rounded-sm bg-[#DEC8B0] flex items-center justify-center text-[#4A392B] font-bold text-[11px] shrink-0 border border-gray-300">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-[#2E2218] truncate">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</p>
                            <p class="text-[10px] text-[#735B44] uppercase tracking-wider font-semibold">{{ auth()->check() ? auth()->user()->role : 'Admin' }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" title="Logout" class="text-[#735B44] hover:text-[#932328] p-1 transition cursor-pointer">
                            <i class="bi bi-box-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOPBAR (Rigid & Functional) -->
            <header class="h-14 px-6 lg:px-8 bg-[#FAF7F2] border-b border-gray-200 flex items-center justify-end sticky top-0 z-30">
                <!-- Right: Profile & Notification -->
                <div class="flex items-center gap-3">
                    <!-- Profile Badge (Rounded-sm, border-gray-200, shadow-sm) -->
                    <div class="flex items-center gap-2 px-3 py-1 rounded-sm bg-white border border-gray-200 shadow-sm">
                        <div class="w-5 h-5 rounded-sm bg-[#DEC8B0] flex items-center justify-center text-[#4A392B] text-xs">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="text-xs font-semibold text-[#2E2218]">
                            {{ auth()->check() ? (auth()->user()->isAdmin() ? 'Admin / Staff' : 'Staff Operasional') : 'Admin / Staff' }}
                        </span>
                    </div>

                    <!-- Notification Bell -->
                    <button class="text-[#2E2218] p-1.5 border border-gray-200 rounded-sm bg-white shadow-sm hover:bg-[#FAF4EC] transition focus:outline-none" title="Notifikasi">
                        <i class="bi bi-bell text-sm"></i>
                    </button>
                </div>
            </header>

            <!-- FLASH NOTIFICATIONS -->
            @if(session('success'))
            <div class="px-6 lg:px-8 pt-3">
                <div class="bg-green-50 border border-green-200 text-green-800 px-3.5 py-2 rounded-sm text-xs flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-green-600"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-800 hover:opacity-75"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            @endif

            <!-- MAIN CONTENT -->
            <main class="flex-1 px-6 lg:px-8 py-5">
                @yield('content')
            </main>

            <!-- FOOTER PERSIS SESUAI INSTRUKSI USER -->
            <footer class="py-3 px-6 lg:px-8 text-center text-xs text-[#735B44] border-t border-gray-200 bg-[#FAF7F2]">
                &copy; 2026 - Naila Safarina - 250180118 - UTS Web Programming 2
            </footer>

        </div>

    </div>

    <!-- Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');
        const openBtn = document.getElementById('open-sidebar-btn');
        const closeBtn = document.getElementById('close-sidebar-btn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    </script>
    @stack('scripts')
</body>
</html>
