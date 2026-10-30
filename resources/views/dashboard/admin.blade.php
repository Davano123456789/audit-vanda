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
                <p class="mt-2 max-w-xl text-sm text-brand-100">Kelola data master dan assessment pengukuran maturity level ITIL V3 Service Operation dari sini.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="rounded-full bg-white/15 px-3 py-1 font-medium">{{ auth()->user()->role_label }}</span>
                <span class="rounded-full bg-white/15 px-3 py-1">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Assessment', $stats['assessments'], 'folder-kanban', "{$stats['in_progress']} berjalan · {$stats['completed']} selesai"],
            ['Subdomain', $stats['processes'], 'workflow', "{$stats['attributes']} process attribute"],
            ['Pertanyaan', $stats['questions'], 'clipboard-list', 'Lembar kerja Level 1 - 5'],
            ['Asesor', $stats['asesors'], 'users', 'Akun asesor terdaftar'],
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
@endsection
