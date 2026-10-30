<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') · @endif{{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

    {{-- Class yang dipakai berulang di semua halaman --}}
    <style type="text/tailwindcss">
        .card { @apply rounded-xl border border-gray-200 bg-white; }
        .card-header { @apply flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4; }

        .btn { @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition; }
        .btn-primary { @apply bg-brand-600 text-white hover:bg-brand-700; }
        .btn-secondary { @apply border border-gray-300 bg-white text-gray-700 hover:bg-gray-50; }
        .btn-icon { @apply rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-brand-700; }
        .btn-icon-danger { @apply rounded-lg p-2 text-gray-500 transition hover:bg-red-50 hover:text-red-600; }

        .label { @apply mb-1.5 block text-sm font-medium text-gray-700; }
        .input { @apply w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100; }
        .input-error { @apply border-red-400 focus:border-red-500 focus:ring-red-100; }
        .error-text { @apply mt-1 text-xs text-red-600; }
        .hint { @apply mt-1 text-xs text-gray-500; }

        .table { @apply w-full text-left text-sm; }
        .table thead th { @apply bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500; }
        .table tbody td { @apply px-4 py-3; }
        .table tbody tr { @apply border-t border-gray-100 hover:bg-gray-50/60; }

        .badge { @apply inline-flex items-center whitespace-nowrap rounded-md px-2 py-0.5 text-xs font-semibold; }
        .badge-gray { @apply bg-gray-100 text-gray-700; }
        .badge-brand { @apply bg-brand-100 text-brand-800; }
        .badge-green { @apply bg-emerald-100 text-emerald-800; }
        .badge-blue { @apply bg-sky-100 text-sky-800; }
        .badge-amber { @apply bg-amber-100 text-amber-800; }
        .badge-orange { @apply bg-orange-100 text-orange-800; }
        .badge-red { @apply bg-red-100 text-red-700; }
        .badge-violet { @apply bg-violet-100 text-violet-800; }

        /* Saat dicetak: warna latar ikut tercetak, elemen .no-print disembunyikan */
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .avoid-break { break-inside: avoid; }
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-surface text-ink antialiased print:bg-white">
    <div class="flex h-screen overflow-hidden print:block print:h-auto print:overflow-visible">

        @include('layouts.partials.sidebar')

        <div class="relative flex min-w-0 flex-1 flex-col overflow-y-auto print:overflow-visible">
            @include('layouts.partials.header')

            <main class="flex-1 px-4 py-6 lg:px-8 print:p-0">
                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();

        // Buka/tutup sidebar di layar kecil
        (function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            const toggle = (open) => {
                sidebar.classList.toggle('-translate-x-full', !open);
                overlay.classList.toggle('hidden', !open);
            };

            document.querySelectorAll('[data-sidebar-open]').forEach(el => el.addEventListener('click', () => toggle(true)));
            document.querySelectorAll('[data-sidebar-close]').forEach(el => el.addEventListener('click', () => toggle(false)));
        })();

        // Konfirmasi sebelum submit untuk form yang punya atribut data-confirm="Pesan".
        // Opsional: data-confirm-text (keterangan) dan data-confirm-button (teks tombol).
        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!form.matches('[data-confirm]')) return;

            event.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: form.dataset.confirm,
                text: form.dataset.confirmText || 'Data yang dihapus tidak dapat dikembalikan.',
                showCancelButton: true,
                confirmButtonText: form.dataset.confirmButton || 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    </script>

    {{-- Notifikasi dari controller: ->with('success'|'error', '...') --}}
    @if (session('success') || session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                icon: @json(session('success') ? 'success' : 'error'),
                title: @json(session('success') ?? session('error')),
            });
        </script>
    @endif

    {{-- Popup hasil evaluasi satu level dari halaman penilaian: ->with('evaluation', [...]) --}}
    @if ($evaluation = session('evaluation'))
        <script>
            Swal.fire({
                icon: @json($evaluation['rating'] === 'F' ? 'success' : 'info'),
                title: @json('Hasil Evaluasi ' . $evaluation['level_label']),
                html: `
                    <p style="font-size:14px;color:#6b7280">{{ e($evaluation['process']) }}</p>
                    <p style="margin-top:14px;font-size:34px;font-weight:700">{{ $evaluation['percent'] }}</p>
                    <p style="font-size:13px;color:#6b7280">Skor {{ $evaluation['score'] + 0 }} dari {{ $evaluation['total'] }} pertanyaan &middot; Rating <b>{{ $evaluation['rating'] }}</b> ({{ $evaluation['rating_name'] }})</p>
                    <p style="margin-top:14px;font-size:14px">
                        @if ($evaluation['completed'])
                            Penilaian selesai. Hasil: <b>Level {{ $evaluation['result_level'] }} ({{ $evaluation['result_name'] }})</b><br>
                            Target Level {{ $evaluation['target'] }} &middot; Gap {{ $evaluation['gap'] }}
                        @else
                            Rating F (&ge; 85%), penilaian dilanjutkan ke <b>{{ $evaluation['next_level_label'] }}</b>.
                        @endif
                    </p>`,
                confirmButtonText: @json($evaluation['completed'] ? 'Lihat Hasil' : 'Lanjutkan'),
                confirmButtonColor: '#4f46e5',
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
