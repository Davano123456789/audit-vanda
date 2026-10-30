@extends('layouts.master')

@section('title', 'Data Assessment')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">{{ $assessment->name }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $assessment->company_name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('penilaian.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            @if (Route::has('laporan.show'))
                <a href="{{ route('laporan.show', $assessment) }}" class="btn btn-primary"><i data-lucide="file-bar-chart" class="h-4 w-4"></i> Report</a>
            @endif
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="font-semibold">Subdomain yang Dinilai</h3>
                <p class="text-xs text-gray-500">Klik tombol aksi untuk membuka lembar kerja penilaian.</p>
            </div>
            @if ($assessment->maturity_index !== null)
                <p class="text-sm">Kematangan domain <span class="font-semibold">{{ $assessment->maturity_display }}</span>
                    <span class="badge badge-{{ config("itil.levels.{$assessment->maturity_level}.color") }} ml-1">Level {{ $assessment->maturity_level }}</span></p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Subdomain</th>
                        <th class="text-center">Pertanyaan</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Hasil</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assessment->processes as $item)
                        <tr>
                            <td>
                                <span class="font-semibold text-brand-700">{{ $item->process_code }}</span>
                                <span class="font-medium">&middot; {{ $item->process->name }}</span>
                            </td>
                            <td class="text-center text-gray-600">{{ $item->process->questions_count }}</td>
                            <td class="text-center">Level {{ $item->target_level }}</td>
                            <td class="text-center">
                                @if ($item->isCompleted())
                                    <span class="badge badge-{{ config("itil.levels.{$item->result_level}.color") }}">Level {{ $item->result_level }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $item->status_color }}">{{ $item->status_label }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('penilaian.workspace', [$assessment, $item->process_code]) }}"
                                   class="btn {{ $item->isCompleted() ? 'btn-secondary' : 'btn-primary' }} py-1.5">
                                    @if ($item->isCompleted())
                                        <i data-lucide="eye" class="h-4 w-4"></i> Lihat Hasil
                                    @elseif ($item->status === 'in_progress')
                                        <i data-lucide="play" class="h-4 w-4"></i> Lanjutkan
                                    @else
                                        <i data-lucide="clipboard-pen" class="h-4 w-4"></i> Mulai Nilai
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
