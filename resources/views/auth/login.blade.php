<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF7F2]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun - InventPro</title>

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
                            300: '#D7C3AD',  // input borders
                            400: '#DEC8B0',  // accent tan
                            500: '#C2A78C',  // warm tan focus
                            600: '#9C8167',  // muted text
                            700: '#735B44',  // secondary dark text
                            800: '#4A392B',  // primary button
                            900: '#2E2218',  // deep espresso
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-[#FAF7F2] text-[#2E2218] font-sans antialiased flex items-center justify-center p-4 selection:bg-[#DEC8B0] selection:text-[#2E2218]">

    <div class="w-full max-w-md">

        <!-- Card Login (Pastel Coksu - Rigid & Functional, rounded-sm, border-gray-200, shadow-sm) -->
        <div class="bg-[#FAF3EA] border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            
            <!-- Header Card (Soft Tan / Coksu) -->
            <div class="p-5 text-center bg-[#EBDCCB] border-b border-gray-200">
                <div class="w-10 h-10 rounded-sm bg-[#DEC8B0] border border-gray-300 mx-auto flex items-center justify-center text-[#4A392B] text-xl mb-2.5 shadow-sm">
                    <i class="bi bi-shop"></i>
                </div>
                <h1 class="text-lg font-bold text-[#2E2218] tracking-tight">InventPro Cemilan &amp; Minuman</h1>
                <p class="text-xs text-[#735B44] mt-0.5 font-medium">Sistem Manajemen Inventaris &amp; Penjualan</p>
            </div>

            <!-- Body Card -->
            <div class="p-5 sm:p-6 space-y-4">
                <div class="text-center mb-1">
                    <h2 class="text-sm font-bold text-[#2E2218]">Masuk ke Akun</h2>
                    <p class="text-xs text-[#735B44] mt-0.5">Silakan login dengan akun yang terdaftar</p>
                </div>

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-3.5">
                    @csrf

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#735B44]">
                                <i class="bi bi-envelope text-xs"></i>
                            </span>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="customer@inventpro.com"
                                   class="w-full pl-8 pr-3 py-2 text-xs sm:text-sm bg-[#FAF7F2] border @error('email') border-red-500 @else border-gray-300 @enderror rounded-sm text-[#2E2218] placeholder-[#9C8167]/60 focus:outline-none focus:border-[#C2A78C] transition">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-700 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-[#5C4A3E] mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#735B44]">
                                <i class="bi bi-key text-xs"></i>
                            </span>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••"
                                   class="w-full pl-8 pr-3 py-2 text-xs sm:text-sm bg-[#FAF7F2] border @error('password') border-red-500 @else border-gray-300 @enderror rounded-sm text-[#2E2218] placeholder-[#9C8167]/60 focus:outline-none focus:border-[#C2A78C] transition">
                        </div>
                        @error('password')
                            <p class="text-xs text-red-700 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                                id="remember" 
                                name="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                                class="w-3.5 h-3.5 rounded-sm text-[#4A392B] border-gray-300 focus:ring-[#C2A78C] cursor-pointer">
                        <label for="remember" class="ml-2 text-xs text-[#735B44] cursor-pointer">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Tombol Masuk -->
                    <div>
                        <button type="submit" 
                                class="w-full py-2 px-4 rounded-sm text-white font-semibold text-xs sm:text-sm bg-[#4A392B] hover:bg-[#382B20] border border-gray-300 transition shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Masuk</span>
                        </button>
                    </div>
                </form>

                <div class="relative my-3">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-[#FAF3EA] px-2 text-[#9C8167]">Akses Cepat Demo</span>
                    </div>
                </div>

                <!-- Pilihan Akun Demo -->
                <div class="p-3 rounded-sm bg-[#FAF7F2] border border-gray-200">
                    <span class="text-xs font-semibold text-[#735B44] block mb-2 text-center">
                        Pilih Akun Demo:
                    </span>
                    <div class="grid grid-cols-3 gap-1.5">
                        <button type="button" 
                                onclick="fillDemo('admin@inventpro.com', 'password')"
                                class="px-2 py-1.5 rounded-sm text-xs font-medium bg-white hover:bg-[#FAF4EC] text-[#4A392B] border border-gray-300 transition text-center truncate cursor-pointer shadow-sm"
                                title="Admin (Owner)">
                            Admin
                        </button>
                        <button type="button" 
                                onclick="fillDemo('staff@inventpro.com', 'password')"
                                class="px-2 py-1.5 rounded-sm text-xs font-medium bg-white hover:bg-[#FAF4EC] text-[#4A392B] border border-gray-300 transition text-center truncate cursor-pointer shadow-sm"
                                title="Staff Kasir">
                            Staff
                        </button>
                        <button type="button" 
                                onclick="fillDemo('customer@inventpro.com', 'password')"
                                class="px-2 py-1.5 rounded-sm text-xs font-medium bg-white hover:bg-[#FAF4EC] text-[#4A392B] border border-gray-300 transition text-center truncate cursor-pointer shadow-sm"
                                title="Pelanggan (Naila Safarina)">
                            Pelanggan
                        </button>
                    </div>
                    <p class="text-center mt-2 text-[11px] text-[#735B44]">
                        Password otomatis: <code class="font-mono bg-white px-1.5 py-0.5 rounded-sm border border-gray-300 text-[#2E2218]">password</code>
                    </p>
                </div>

            </div>

        </div>

        <p class="text-center text-xs text-[#735B44] mt-4">
            &copy; 2026 - Naila Safarina - 250180118 - UTS Web Programming 2
        </p>

    </div>

    <script>
        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>