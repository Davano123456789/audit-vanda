<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') · {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] } } } };
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md text-center">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                <i data-lucide="@yield('icon', 'alert-triangle')" class="h-8 w-8"></i>
            </div>

            <p class="text-5xl font-bold text-indigo-700">@yield('code')</p>
            <h1 class="mt-3 text-xl font-bold">@yield('title')</h1>
            <p class="mt-2 text-sm text-slate-500">@yield('message')</p>

            <div class="mt-8 flex flex-wrap justify-center gap-2">
                <button type="button" onclick="history.back()"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium hover:bg-slate-50">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
                </button>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <i data-lucide="home" class="h-4 w-4"></i> Ke Dashboard
                </a>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
