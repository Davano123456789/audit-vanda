<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        surface: '#f5f6fa',
                        ink: '#1e2235',
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81',
                        },
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-surface text-ink antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl md:grid md:grid-cols-2">

            {{-- Panel informasi --}}
            <div class="relative hidden flex-col gap-10 overflow-hidden bg-brand-900 p-10 text-white md:flex">
                <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-10 h-56 w-56 rounded-full bg-brand-500/20"></div>

                <div class="relative flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-brand-700">
                        <i data-lucide="gauge" class="h-5 w-5"></i>
                    </div>
                    <span class="text-sm font-semibold text-brand-100">ITIL V3 &middot; Service Operation</span>
                </div>

                <div class="relative">
                    <h2 class="text-2xl font-bold leading-snug">{{ config('app.name') }}</h2>
                    <p class="mt-3 text-sm text-brand-100">
                        Ukur tingkat kematangan manajemen layanan TI pada subdomain Event, Incident, Problem,
                        Request Fulfilment, dan Access Management.
                    </p>
                </div>
            </div>

            {{-- Form login --}}
            <div class="p-8 sm:p-10">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold">Masuk</h1>
                    <p class="mt-1 text-sm text-gray-500">Silakan masuk menggunakan akun Anda.</p>
                </div>

                @if (session('success'))
                    <div class="mb-5 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autofocus
                                   autocomplete="username" placeholder="nama@email.com"
                                   @class([
                                       'w-full rounded-lg border py-2.5 pl-10 pr-3 text-sm outline-none transition focus:ring-2',
                                       'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('email'),
                                       'border-gray-300 focus:border-brand-500 focus:ring-brand-100' => ! $errors->has('email'),
                                   ])>
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••"
                                   @class([
                                       'w-full rounded-lg border py-2.5 pl-10 pr-10 text-sm outline-none transition focus:ring-2',
                                       'border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has('password'),
                                       'border-gray-300 focus:border-brand-500 focus:ring-brand-100' => ! $errors->has('password'),
                                   ])>
                            <button type="button" id="toggle-password" aria-label="Tampilkan password"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        Ingat saya
                    </label>

                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                        <i data-lucide="log-in" class="h-4 w-4"></i>
                        Login
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();

        document.getElementById('toggle-password').addEventListener('click', function () {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>
