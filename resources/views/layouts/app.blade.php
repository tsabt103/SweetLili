<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - SweetLiliSweetLili </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-cream-100 font-sans antialiased text-sage-900 selection:bg-terracotta-100 selection:text-terracotta-800"
    x-data="{ 
        sidebarOpen: false,
        userDropdown: false,
        toastMessage: '',
        toastType: 'success',
        showToast(msg, type = 'success') {
            this.toastMessage = msg;
            this.toastType = type;
            setTimeout(() => { this.toastMessage = ''; }, 4000);
        }
    }">

    <div class="min-h-screen flex flex-col">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-sage-950/40 backdrop-blur-sm md:hidden"
             @click="sidebarOpen = false"
             x-cloak>
        </div>

        <!-- Sidebar Navigation -->
        <aside
  class="w-full bg-white border-b border-sage-200/80 flex flex-col md:flex-row md:items-center md:justify-between sticky top-0 z-50">
            
            <div>
                <!-- Brand / Logo Header -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-sage-100">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-logo size="md" />
                    </a>
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="md:hidden text-sage-400 hover:text-sage-600 focus:outline-none p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex flex-wrap items-center gap-2 px-3 py-3">
                    <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-sage-400">
                        Menu Utama
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-terracotta-500 text-white shadow-sm shadow-terracotta-500/30' : 'text-sage-700 hover:bg-sage-50 hover:text-sage-900' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-sage-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Produk (Livewire CRUD) -->
                    <a href="{{ route('products.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('products.index') ? 'bg-terracotta-500 text-white shadow-sm shadow-terracotta-500/30' : 'text-sage-700 hover:bg-sage-50 hover:text-sage-900' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('products.index') ? 'text-white' : 'text-sage-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="flex-1">Katalog Produk</span>
                        @php
                            $lowCount = \App\Models\Product::lowStock()->count();
                        @endphp
                        @if($lowCount > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ request()->routeIs('products.index') ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-700' }}" title="Produk stok menipis">
                                {{ $lowCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Trash / Sampah Produk -->
                    <a href="{{ route('products.trash') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('products.trash') ? 'bg-terracotta-500 text-white shadow-sm shadow-terracotta-500/30' : 'text-sage-700 hover:bg-sage-50 hover:text-sage-900' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('products.trash') ? 'text-white' : 'text-sage-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="flex-1">Sampah (Trash)</span>
                        @php
                            $trashCount = \App\Models\Product::onlyTrashed()->count();
                        @endphp
                        @if($trashCount > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ request()->routeIs('products.trash') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700' }}">
                                {{ $trashCount }}
                            </span>
                        @endif
                    </a>

                    <div class="pt-3 px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-sage-400">
                        Integrasi & Sistem
                    </div>

                    <!-- REST API Explorer & Docs -->
                    <a href="{{ route('api.docs') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('api.docs') ? 'bg-terracotta-500 text-white shadow-sm shadow-terracotta-500/30' : 'text-sage-700 hover:bg-sage-50 hover:text-sage-900' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('api.docs') ? 'text-white' : 'text-sage-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>REST API & Token</span>
                    </a>
                </nav>
            </div>

            <!-- User mini profile in sidebar -->
            <div class="p-4 border-t border-sage-100 bg-cream-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-sage-200 text-sage-800 font-bold flex items-center justify-center shrink-0 text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-sage-900 truncate">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                            <p class="text-[11px] text-sage-500 truncate">{{ Auth::user()->email ?? 'admin' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar dari sistem" class="p-1.5 text-sage-400 hover:text-terracotta-600 rounded-lg hover:bg-sage-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-sage-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30">
                <!-- Mobile Menu Button & Title -->
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden text-sage-600 hover:text-sage-900 focus:outline-none p-1.5 rounded-lg border border-sage-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-sage-900 leading-tight">{{ $header ?? $title ?? 'Dashboard' }}</h1>
                        @if(isset($subtitle))
                            <p class="text-xs text-sage-500 hidden sm:block">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>

                <!-- Right Header Actions (Status, User dropdown) -->
                <div class="flex items-center gap-3">
                    <!-- Quick System Status Badge -->
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sage-50 text-sage-700 border border-sage-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Laragon Local (Online)</span>
                    </div>

                    <!-- User Profile Dropdown via Alpine.js -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-sage-50 transition-colors focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-sage-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:inline-block text-xs font-semibold text-sage-800">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <svg class="w-4 h-4 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg border border-sage-200/80 py-1 z-50 focus:outline-none"
                             x-cloak>
                            <div class="px-4 py-2 border-b border-sage-100">
                                <p class="text-xs font-medium text-sage-500">Masuk sebagai:</p>
                                <p class="text-xs font-bold text-sage-900 truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xs text-sage-700 hover:bg-sage-50">
                                Statistik Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" class="block px-4 py-2 text-xs text-sage-700 hover:bg-sage-50">
                                Kelola Produk
                            </a>
                            <a href="{{ route('api.docs') }}" class="block px-4 py-2 text-xs text-sage-700 hover:bg-sage-50">
                                API Token & Dokumentasi
                            </a>
                            <div class="border-t border-sage-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-terracotta-600 hover:bg-terracotta-50 font-medium">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Global Toast Notification Component (Alpine.js) -->
            <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none">
                <!-- Session Success Toast -->
                @if (session('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 4500)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                         class="pointer-events-auto bg-white border border-emerald-200 text-emerald-900 px-4 py-3 rounded-2xl shadow-xl shadow-emerald-950/10 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="text-xs font-medium leading-snug">
                            {{ session('success') }}
                        </div>
                        <button @click="show = false" class="text-sage-400 hover:text-sage-600 p-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                <!-- Session Error Toast -->
                @if (session('error'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                         class="pointer-events-auto bg-white border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl shadow-xl shadow-rose-950/10 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="text-xs font-medium leading-snug">
                            {{ session('error') }}
                        </div>
                        <button @click="show = false" class="text-sage-400 hover:text-sage-600 p-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                <!-- Alpine Dynamic Dispatched Toast -->
                <div x-show="toastMessage !== ''" 
                     x-transition
                     class="pointer-events-auto bg-white border px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3"
                     :class="toastType === 'success' ? 'border-emerald-200 text-emerald-900' : 'border-rose-200 text-rose-900'"
                     x-cloak>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                         :class="toastType === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="text-xs font-medium leading-snug" x-text="toastMessage"></div>
                    <button @click="toastMessage = ''" class="text-sage-400 hover:text-sage-600 p-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Page Body Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-4 px-6 border-t border-sage-200/60 text-center text-xs text-sage-500 bg-white/50">
                &copy; {{ date('Y') }} SweetLili - SweetLili  Management System. Dibangun dengan Laravel & Livewire.
            </footer>
        </div>
    </div>

    @livewireScripts
</body>
</html>
