<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun Baru - SweetLili &  Supply System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream-100 font-sans antialiased text-sage-900 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 selection:bg-terracotta-100 selection:text-terracotta-800">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="{{ url('/') }}" class="inline-flex justify-center mb-4">
            <x-logo size="lg" />
        </a>
        <h2 class="text-2xl font-extrabold tracking-tight text-sage-900">
            Daftar Akun Pengelola
        </h2>
        <p class="mt-1 text-sm text-sage-600">
            Buat akun baru untuk mengelola inventaris toko seni & Kue
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        @if ($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800">
                <div class="font-semibold flex items-center gap-1.5 mb-1 text-rose-900">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Terjadi kesalahan input:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white py-8 px-6 shadow-sm border border-sage-200/80 rounded-2xl sm:px-10">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-medium text-sage-800">
                        Nama Lengkap
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="block w-full pl-10 pr-3 py-2.5 border border-sage-300 rounded-xl text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500 text-sm transition-all"
                            placeholder="Contoh: Tsabita iana">
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-sage-800">
                        Alamat Email
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                            class="block w-full pl-10 pr-3 py-2.5 border border-sage-300 rounded-xl text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500 text-sm transition-all"
                            placeholder="nama@domain.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-sage-800">
                        Kata Sandi (Minimal 8 Karakter)
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full pl-10 pr-3 py-2.5 border border-sage-300 rounded-xl text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500 text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-sage-800">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="block w-full pl-10 pr-3 py-2.5 border border-sage-300 rounded-xl text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500 text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-terracotta-500 hover:bg-terracotta-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-terracotta-500 transition-colors shadow-terracotta-500/20">
                        Daftar & Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-sage-100 pt-5 text-center">
                <p class="text-xs text-sage-600">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-semibold text-terracotta-600 hover:text-terracotta-700 ml-1">
                        Masuk di Sini &rarr;
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
