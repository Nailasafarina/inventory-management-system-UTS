<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF7F2]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog') - Inventory & Sales</title>
    
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
                            100: '#FAF4EC',  // soft cream highlight
                            150: '#FAF3EA',  // card background
                            200: '#EBDCCB',  // header & main tan
                            250: '#E2D1BD',  // subtle borders
                            300: '#D7C3AD',  // input borders & dividers
                            400: '#DEC8B0',  // accent tan / badges
                            500: '#C2A78C',  // warm tan accent / focus
                            600: '#9C8167',  // muted text
                            700: '#735B44',  // secondary dark text
                            800: '#4A392B',  // primary buttons & headings
                            900: '#2E2218',  // deep espresso text
                        }
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            height: 5px;
            width: 5px;
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
</head>
<body class="h-full flex flex-col font-sans bg-[#FAF7F2] text-[#2E2218] antialiased text-sm selection:bg-[#DEC8B0] selection:text-[#2E2218]">

    <!-- Top Navigation Bar (Pastel Coksu - Rigid & Functional, border-gray-200) -->
    <header class="bg-[#EBDCCB] text-[#2E2218] border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Name -->
                <div class="flex items-center gap-3">
                    <a href="{{ auth()->check() && auth()->user()->isCustomer() ? route('customer.dashboard') : route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-sm bg-[#DEC8B0] border border-gray-300 flex items-center justify-center text-[#4A392B] text-sm shadow-sm group-hover:bg-[#FAF4EC] transition">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div>
                            <span class="text-base font-bold tracking-tight text-[#2E2218] flex items-center gap-1">
                                InventPro
                            </span>
                            <span class="block text-[10px] text-[#735B44] font-medium leading-none">
                                Inventory &amp; Sales Cemilan &amp; Minuman
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-1.5">
                    @auth
                        @if(auth()->user()->isCustomer())
                        <a href="{{ route('customer.dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-sm border border-gray-200 {{ request()->routeIs('customer.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#5C4A3E] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }} transition flex items-center gap-1.5">
                            <i class="bi bi-shop text-xs text-[#735B44]"></i>
                            <span>Katalog &amp; Belanja Saya</span>
                        </a>
                        @else
                        <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-sm border border-gray-200 {{ request()->routeIs('dashboard') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#5C4A3E] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }} transition flex items-center gap-1.5">
                            <i class="bi bi-house text-xs text-[#735B44]"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-sm border border-gray-200 {{ request()->routeIs('products.*') ? 'bg-[#FAF4EC] text-[#2E2218] shadow-sm border-gray-300' : 'text-[#5C4A3E] bg-[#FAF3EA]/40 hover:text-[#2E2218] hover:bg-[#FAF4EC]' }} transition flex items-center gap-1.5">
                            <i class="bi bi-box text-xs text-[#735B44]"></i>
                            <span>Produk</span>
                        </a>
                        @endif
                    @endauth
                </nav>

                <!-- User Profile & Action -->
                <div class="flex items-center gap-2.5">
                    @auth
                    <!-- Profile Chip -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-sm bg-white border border-gray-200 shadow-sm">
                        <div class="w-5 h-5 rounded-sm bg-[#DEC8B0] flex items-center justify-center text-[#4A392B] font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-xs font-semibold text-[#2E2218] max-w-[130px] truncate">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded-sm bg-[#EBDCCB] text-[#735B44] uppercase font-semibold">
                            {{ auth()->user()->role }}
                        </span>
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin keluar?');" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-sm text-[#735B44] hover:text-[#2E2218] hover:bg-[#FAF4EC] bg-white border border-gray-200 shadow-sm transition"
                                title="Keluar">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-sm text-white bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 shadow-sm transition">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk</span>
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Alert Success -->
            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-300 text-green-800 px-3.5 py-2 rounded-sm text-xs flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-green-600"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-800 hover:opacity-75">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            <!-- Flash Alert Error -->
            @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-3.5 py-2 rounded-sm text-xs flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-800 hover:opacity-75">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- FOOTER PERSIS SESUAI INSTRUKSI USER -->
    <footer class="py-3 border-t border-gray-200 text-center text-xs text-[#735B44] bg-[#FAF7F2]">
        <div class="max-w-7xl mx-auto px-4">
            &copy; 2026 - Naila Safarina - 250180118 - UTS Web Programming 2
        </div>
    </footer>

    @stack('scripts')
</body>
</html>