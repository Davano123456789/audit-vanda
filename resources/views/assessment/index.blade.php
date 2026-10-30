@extends('layouts.master')

@section('title', 'Assessment')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Assessment</h2>
            <p class="mt-1 text-sm text-gray-500">Proyek penilaian maturity level ITIL V3 yang ditugaskan kepada asesor.</p>
        </div>
        <a href="{{ route('assessment.create') }}" class="btn btn-primary"><i data-lucide="plus" class="h-4 w-4"></i> Create</a>
    </div>

    <div class="card">
        {{-- Pencarian & filter --}}
        <form method="GET" class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama assessment atau perusahaan..." class="input lg:flex-1">
            <select name="status" class="input lg:w-40">
                <option value="">Semua status</option>
                @foreach (\App\Models\Assessment::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="asesor" class="input lg:w-48">
                <option value="">Semua asesor</option>
                @foreach ($asesorOptions as $id => $name)
                    <option value="{{ $id }}" @selected((int) request('asesor') === $id)>{{ $name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
                @if (request()->hasAny(['q', 'status', 'asesor']))
                    <a href="{{ route('assessment.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Assessment</th>
                        <th>Asesor</th>
                        <th>Progres</th>
                        <th>Kematangan Domain</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assessments as $assessment)
                        @php
                            $percent = $assessment->processes_count ? round($assessment->completed_processes_count / $assessment->processes_count * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('assessment.show', $assessment) }}" class="font-medium hover:text-brand-700">{{ $assessment->name }}</a>
                                <p class="text-xs text-gray-500">{{ $assessment->company_name }}</p>
                            </td>
                            <td class="text-gray-600">{{ $assessment->asesor->name }}</td>
                            <td class="w-44">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full bg-brand-500" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="whitespace-nowrap text-xs font-medium text-gray-600">{{ $assessment->completed_processes_count }}/{{ $assessment->processes_count }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($assessment->maturity_index !== null)
                                    <span class="font-semibold">{{ $assessment->maturity_display }}</span>
                                    <span class="badge badge-{{ config("itil.levels.{$assessment->maturity_level}.color") }} ml-1">Level {{ $assessment->maturity_level }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $assessment->status_color }}">{{ $assessment->status_label }}</span>
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('assessment.show', $assessment) }}" class="btn-icon" title="Detail"><i data-lucide="eye" class="h-4 w-4"></i></a>
                                    @if (Route::has('laporan.show'))
                                        <a href="{{ route('laporan.show', $assessment) }}" class="btn-icon" title="Report"><i data-lucide="file-bar-chart" class="h-4 w-4"></i></a>
                                    @endif
                                    <a href="{{ route('assessment.edit', $assessment) }}" class="btn-icon" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('assessment.destroy', $assessment) }}"
                                          data-confirm="Hapus assessment {{ $assessment->name }}?"
                                          data-confirm-text="Semua jawaban pada assessment ini ikut terhapus.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-sm text-gray-500">Belum ada assessment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($assessments->hasPages())
            <div class="border-t border-gray-100 p-4">{{ $assessments->links() }}</div>
        @endif
    </div>
@endsection
