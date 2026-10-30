@extends('layouts.master')

@section('title', 'Detail Assessment')

@section('content')
    @php
        $total = $assessment->processes->count();
        $completed = $assessment->processes->where('status', 'completed')->count();
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">{{ $assessment->name }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $assessment->company_name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('assessment.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <a href="{{ route('assessment.edit', $assessment) }}" class="btn btn-secondary"><i data-lucide="pencil" class="h-4 w-4"></i> Edit</a>
            @if (Route::has('laporan.show'))
                <a href="{{ route('laporan.show', $assessment) }}" class="btn btn-primary"><i data-lucide="file-bar-chart" class="h-4 w-4"></i> Report</a>
            @endif
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card p-5">
            <p class="text-sm text-gray-500">Status</p>
            <p class="mt-2">
                <span class="badge badge-{{ $assessment->status_color }}">{{ $assessment->status_label }}</span>
            </p>
            <p class="mt-2 text-xs text-gray-500">Dibuat {{ $assessment->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500">Asesor</p>
            <p class="mt-1 font-semibold">{{ $assessment->asesor->name }}</p>
            <p class="text-xs text-gray-500">{{ $assessment->asesor->email }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500">Progres Penilaian</p>
            <p class="mt-1 text-3xl font-bold">{{ $completed }}/{{ $total }}</p>
            <p class="text-xs text-gray-500">subdomain selesai dinilai</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500">Kematangan Domain</p>
            @if ($assessment->maturity_index !== null)
                <p class="mt-1 text-3xl font-bold">{{ $assessment->maturity_display }}</p>
                <span class="badge badge-{{ config("itil.levels.{$assessment->maturity_level}.color") }}">Level {{ $assessment->maturity_level }} &middot; {{ $assessment->maturity_level_name }}</span>
            @else
                <p class="mt-1 text-3xl font-bold text-gray-300">-</p>
                <p class="text-xs text-gray-500">Belum ada subdomain selesai</p>
            @endif
        </div>
    </div>

    @if ($assessment->description)
        <div class="card mb-6 space-y-2 p-5 text-sm">
            @if ($assessment->description)
                <p class="text-gray-600">{{ $assessment->description }}</p>
            @endif
        </div>
    @endif

    {{-- Subdomain --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="font-semibold">Subdomain yang Dinilai</h3>
                <p class="text-xs text-gray-500">Gap = Target Level − Current Level (Rumus 2.4).</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Subdomain</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Current Level</th>
                        <th class="text-center">Gap</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assessment->processes as $item)
                        <tr>
                            <td>
                                <span class="font-semibold text-brand-700">{{ $item->process_code }}</span>
                                <span class="font-medium">&middot; {{ $item->process->name }}</span>
                            </td>
                            <td class="text-center">Level {{ $item->target_level }}</td>
                            <td class="text-center">
                                @if ($item->isCompleted())
                                    <span class="badge badge-{{ config("itil.levels.{$item->result_level}.color") }}">Level {{ $item->result_level }} &middot; {{ \App\Support\Itil::levelName($item->result_level) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->gap === null)
                                    <span class="text-gray-400">-</span>
                                @elseif ($item->gap === 0)
                                    <span class="badge badge-green">0</span>
                                @else
                                    <span class="badge badge-red">{{ $item->gap }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $item->status_color }}">{{ $item->status_label }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
