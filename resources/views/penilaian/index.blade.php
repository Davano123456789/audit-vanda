@extends('layouts.master')

@section('title', 'Assessment Saya')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Assessment Saya</h2>
        <p class="mt-1 text-sm text-gray-500">Assessment yang ditugaskan kepada Anda. Pilih assessment untuk mulai menilai.</p>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12">No</th>
                        <th>Assessment</th>
                        <th>Progres</th>
                        <th>Kematangan Domain</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assessments as $assessment)
                        @php
                            $percent = $assessment->processes_count ? round($assessment->completed_processes_count / $assessment->processes_count * 100) : 0;
                        @endphp
                        <tr>
                            <td class="text-gray-500">{{ $loop->iteration }}.</td>
                            <td>
                                <a href="{{ route('penilaian.show', $assessment) }}" class="font-medium hover:text-brand-700">{{ $assessment->name }}</a>
                                <p class="text-xs text-gray-500">{{ $assessment->company_name }}</p>
                            </td>
                            <td class="w-48">
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
                            <td><span class="badge badge-{{ $assessment->status_color }}">{{ $assessment->status_label }}</span></td>
                            <td class="text-right">
                                <a href="{{ route('penilaian.show', $assessment) }}" class="btn btn-primary py-1.5">
                                    <i data-lucide="clipboard-pen" class="h-4 w-4"></i> Assessment
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-sm text-gray-500">Belum ada assessment yang ditugaskan kepada Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
