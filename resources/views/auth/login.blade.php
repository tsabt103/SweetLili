<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk - SWEETLILI</title>

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
            Masuk ke Sistem
        </h2>
        <p class="mt-1 text-sm text-sage-600">
            Kelola inventaris dan katalog perlengkapan Kue & seni
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <!-- Demo Credentials Helper Banner -->
        <div class="mb-4 p-3.5 rounded-xl bg-sage-50 border border-sage-200 text-xs text-sage-800 flex items-start gap-2.5 shadow-sm">
            <svg class="w-5 h-5 text-sage-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <span class="font-bold text-sage-900">Akun Demo Seeder:</span><br>
                Email: <code class="font-mono bg-sage-100 px-1 py-0.5 rounded text-sage-900">admin@SweetLili.test</code><br>
                Kata Sandi: <code class="font-mono bg-sage-100 px-1 py-0.5 rounded text-sage-900">password123</code>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800">
                <div class="font-semibold flex items-center gap-1.5 mb-1 text-rose-900">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Periksa kembali data Anda:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white py-8 px-6 shadow-sm border border-sage-200/80 rounded-2xl sm:px-10">
            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPass: false }">
                @csrf

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
                        <input id="email" name="email" type="email" value="{{ old('email', 'admin@SweetLili.test') }}" required autofocus autocomplete="username"
                            class="block w-full pl-10 pr-3 py-2.5 border border-sage-300 rounded-xl text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500 text-sm transition-all"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-sage-800">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sage-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" :type="showPass ? 'text' : 'password'" required autocomplete="current-password"
                            class="block w-full pl-10 pr-10 py-2.5 border border-sage-300 rounded-xl text-sage-900 placeholder-sage-400 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:border-sage-500 text-sm transition-all"
                            placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600 focus:outline-none">
                            <svg x-show="!showPass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPass" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-sage-300 text-terracotta-500 focus:ring-terracotta-400">
                        <span class="text-sage-700">Ingat sesi saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-terracotta-500 hover:bg-terracotta-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-terracotta-500 transition-colors shadow-terracotta-500/20">
                        Masuk ke Akun
                    </button>
                </div>
            </form>

            <div class="mt-6 border-t border-sage-100 pt-5 text-center">
                <p class="text-xs text-sage-600">
                    Belum memiliki akun pengelola?
                    <a href="{{ route('register') }}" class="font-semibold text-terracotta-600 hover:text-terracotta-700 ml-1">
                        Daftar Akun Baru &rarr;
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
