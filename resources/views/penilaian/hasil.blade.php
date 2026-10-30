@extends('layouts.master')

@section('title', 'Hasil ' . $item->process_code)

@section('content')
    @php
        $levelLabels = \App\Models\ProcessAttribute::LEVEL_LABELS;
        $resultLevel = $result['result_level'];
        $gap = $resultLevel === null ? null : max(0, $item->target_level - $resultLevel);
        $answerLabels = \App\Models\Answer::LABELS;
        $answerColors = ['Y' => 'green', 'P' => 'amber', 'N' => 'red'];
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Hasil {{ $item->process_code }} &middot; {{ $item->process->name }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $assessment->name }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('penilaian.show', $assessment) }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            @if (Route::has('laporan.show'))
                <a href="{{ route('laporan.show', $assessment) }}" class="btn btn-primary"><i data-lucide="file-bar-chart" class="h-4 w-4"></i> Report</a>
            @endif
        </div>
    </div>

    @if ($result['status'] === \App\Services\MaturityAssessor::EMPTY)
        <div class="card px-5 py-12 text-center">
            <i data-lucide="clipboard-x" class="mx-auto mb-2 h-10 w-10 text-gray-300"></i>
            <p class="text-sm text-gray-500">Subdomain ini belum memiliki pertanyaan. Hubungi admin untuk menambahkan pertanyaan.</p>
        </div>
    @else
        {{-- Ringkasan hasil --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="card p-5 sm:col-span-1">
                <p class="text-sm text-gray-500">Maturity Level</p>
                <p class="mt-1 text-4xl font-bold">Level {{ $resultLevel }}</p>
                <span class="badge badge-{{ config("itil.levels.{$resultLevel}.color") }} mt-1">{{ \App\Support\Itil::levelName($resultLevel) }} &middot; {{ \App\Support\Itil::levelLabel($resultLevel) }}</span>
            </div>
            <div class="card p-5">
                <p class="text-sm text-gray-500">Target Level</p>
                <p class="mt-1 text-4xl font-bold">Level {{ $item->target_level }}</p>
                <p class="text-xs text-gray-500">{{ \App\Support\Itil::levelName($item->target_level) }}</p>
            </div>
            <div class="card p-5">
                <p class="text-sm text-gray-500">Gap (Target − Current)</p>
                <p class="mt-1 text-4xl font-bold {{ $gap === 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $gap }}</p>
                <p class="text-xs text-gray-500">{{ $gap === 0 ? 'Target sudah tercapai' : 'Perlu peningkatan ' . $gap . ' level' }}</p>
            </div>
        </div>

        <div class="card mb-6 p-5 text-sm text-gray-600">
            {{ config("itil.levels.{$resultLevel}.description") }}
        </div>

        {{-- Hasil per level & per atribut --}}
        <div class="card mb-6">
            <div class="card-header">
                <div>
                    <h3 class="font-semibold">Hasil Pengukuran Level {{ $item->process->name }}</h3>
                    <p class="text-xs text-gray-500">Nilai per atribut dan nilai level = Σ skor ÷ Σ pertanyaan × 100%.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Process Attribute</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Jumlah Pertanyaan</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Rating</th>
                            <th>Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result['history'] as $level => $history)
                            @continue($history['status'] === 'skipped')
                            @foreach ($history['attributes'] as $code => $attribute)
                                <tr>
                                    @if ($loop->first)
                                        <td rowspan="{{ count($history['attributes']) + 1 }}" class="align-top font-semibold">{{ $levelLabels[$level] }}</td>
                                    @endif
                                    <td><span class="font-semibold text-brand-700">{{ $code }}</span> &middot; {{ $attribute['name'] }}</td>
                                    <td class="text-center">{{ $attribute['score'] + 0 }}</td>
                                    <td class="text-center">{{ $attribute['total'] }}</td>
                                    <td class="text-center">{{ \App\Support\Itil::formatPercent($attribute['percent']) }}</td>
                                    <td class="text-center"><span class="badge badge-{{ config("itil.ratings.{$attribute['rating']}.color") }}">{{ $attribute['rating'] }}</span></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-semibold">
                                <td>Nilai {{ $levelLabels[$level] }}</td>
                                <td class="text-center">{{ $history['score'] + 0 }}</td>
                                <td class="text-center">{{ $history['total'] }}</td>
                                <td class="text-center">{{ \App\Support\Itil::formatPercent($history['percent']) }}</td>
                                <td class="text-center"><span class="badge badge-{{ config("itil.ratings.{$history['rating']}.color") }}">{{ $history['rating'] }}</span></td>
                                <td class="text-xs font-medium">{{ \App\Services\MaturityAssessor::decisionText($level, $history['rating'], $level < max($result['levels'])) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rincian jawaban --}}
        <div class="card">
            <div class="card-header"><h3 class="font-semibold">Rincian Jawaban</h3></div>
            <div class="divide-y divide-gray-100">
                @foreach ($answersByLevel as $level => $attributes)
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between px-5 py-3 hover:bg-gray-50">
                            <span class="font-medium">{{ $levelLabels[$level] }}</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-400 transition group-open:rotate-180"></i>
                        </summary>
                        <div class="space-y-4 px-5 pb-5">
                            @foreach ($attributes as $code => $answers)
                                <div>
                                    <p class="mb-2 text-sm font-semibold"><span class="text-brand-700">{{ $code }}</span> &middot; {{ $answers->first()->question->attribute->name }}</p>
                                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="w-12">No</th>
                                                    <th>Pertanyaan</th>
                                                    <th class="w-28 text-center">Jawaban</th>
                                                    <th class="w-16 text-center">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($answers as $answer)
                                                    <tr class="align-top">
                                                        <td class="text-gray-500">{{ $answer->question->number }}.</td>
                                                        <td>{{ $answer->question->question_text }}</td>
                                                        <td class="text-center"><span class="badge badge-{{ $answerColors[$answer->answer] }}">{{ $answer->answer }} &middot; {{ $answerLabels[$answer->answer] }}</span></td>
                                                        <td class="text-center">{{ $answer->score + 0 }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>
        </div>

            <form method="POST" action="{{ route('penilaian.reset', [$assessment, $item->process_code]) }}" class="mt-6 text-right"
                  data-confirm="Reset penilaian {{ $item->process_code }}?"
                  data-confirm-text="Semua jawaban subdomain ini akan dihapus, penilaian dimulai lagi dari Level 1 & 2."
                  data-confirm-button="Ya, reset">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:underline">
                    <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i> Reset dan nilai ulang subdomain ini
                </button>
            </form>
    @endif
@endsection
