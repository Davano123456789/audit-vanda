@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    @php
        $hour = (int) now()->format('G');
        $greeting = match (true) {
            $hour >= 4 && $hour < 11 => 'Selamat pagi',
            $hour >= 11 && $hour < 15 => 'Selamat siang',
            $hour >= 15 && $hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };
    @endphp

    {{-- Sapaan --}}
    <div class="relative mb-6 overflow-hidden rounded-xl bg-gradient-to-r from-brand-900 to-brand-600 px-6 py-7 text-white">
        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm text-brand-100">{{ $greeting }},</p>
                <h2 class="mt-0.5 text-2xl font-bold">{{ auth()->user()->name }}</h2>
                <p class="mt-2 max-w-xl text-sm text-brand-100">Lanjutkan penilaian maturity level pada assessment yang ditugaskan kepada Anda.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="rounded-full bg-white/15 px-3 py-1 font-medium">{{ auth()->user()->role_label }}</span>
                <span class="rounded-full bg-white/15 px-3 py-1">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['Assessment Ditugaskan', $stats['assessments'], 'folder-kanban', "{$stats['assessments_completed']} assessment selesai"],
            ['Subdomain Selesai Dinilai', "{$stats['processes_completed']}/{$stats['processes']}", 'list-checks', 'Total subdomain dari semua assessment'],
            ['Sisa Subdomain', $stats['processes'] - $stats['processes_completed'], 'hourglass', 'Belum selesai dinilai'],
        ] as [$label, $value, $icon, $hint])
            <div class="card flex items-start justify-between gap-3 p-5">
                <div>
                    <p class="text-sm text-gray-500">{{ $label }}</p>
                    <p class="mt-1 text-3xl font-bold">{{ $value }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                    <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
                </div>
            </div>
        @endforeach
    </div>

    <div>
        {{-- Subdomain yang belum selesai dinilai --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="font-semibold">Lanjutkan Penilaian</h3>
                    <p class="text-xs text-gray-500">Subdomain yang belum selesai dinilai.</p>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($pending as $item)
                    <a href="{{ Route::has('penilaian.workspace') ? route('penilaian.workspace', [$item->assessment_id, $item->process_code]) : '#' }}"
                       class="flex items-center justify-between gap-3 px-5 py-3 transition hover:bg-gray-50">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $item->process_code }} &middot; {{ $item->process->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $item->assessment->name }}</p>
                        </div>
                        <span class="badge badge-{{ $item->status_color }}">{{ $item->status_label }}</span>
                    </a>
                @empty
                    <div class="px-5 py-10 text-center">
                        <i data-lucide="party-popper" class="mx-auto mb-2 h-8 w-8 text-brand-500"></i>
                        <p class="text-sm text-gray-500">Tidak ada subdomain yang menunggu penilaian.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
