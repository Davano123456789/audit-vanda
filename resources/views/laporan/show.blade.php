@extends('layouts.master')

@section('title', 'Report Assessment')

@push('styles')
    <style>
        @page { size: A4; margin: 15mm; }
    </style>
@endpush

@section('content')
    @php
        $assessment = $report['assessment'];
        $rows = $report['rows'];
        $attributes = $report['attributes'];
        $attributesByLevel = $attributes->groupBy('level');
        $levelLabels = \App\Models\ProcessAttribute::LEVEL_LABELS;
        $max = config('itil.max_level');
        $backUrl = auth()->user()->isAdmin() ? route('assessment.show', $assessment) : route('penilaian.show', $assessment);
        $answerColors = ['Y' => 'green', 'P' => 'amber', 'N' => 'red'];
    @endphp

    {{-- Tombol aksi (tidak ikut dicetak) --}}
    <div class="no-print mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ $backUrl }}" class="btn btn-secondary w-fit"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
        <div class="flex flex-wrap items-center gap-3">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" id="toggle-detail" class="h-4 w-4 rounded border-gray-300 text-brand-600">
                Sertakan rincian jawaban
            </label>
            <button type="button" onclick="window.print()" class="btn btn-primary"><i data-lucide="printer" class="h-4 w-4"></i> Cetak / PDF</button>
        </div>
    </div>

    <div class="card p-6 print:border-0 print:p-0 sm:p-8">
        {{-- Kop laporan --}}
        <div class="border-b-2 border-ink pb-4 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Laporan Hasil Pengukuran</p>
            <h2 class="mt-1 text-xl font-bold">Tingkat Kematangan Manajemen Layanan TI</h2>
            <p class="text-sm font-medium">Framework ITIL V3 Domain Service Operation</p>
            <p class="mt-1 text-sm text-gray-600">{{ $assessment->company_name }}</p>
        </div>

        <table class="mt-4 text-sm">
            <tbody>
                @foreach ([
                    'Nama Assessment' => $assessment->name,
                    'Perusahaan' => $assessment->company_name,
                    'Asesor' => $assessment->asesor->name,
                    'Tanggal Laporan' => now()->translatedFormat('d F Y'),
                    'Status' => $assessment->status_label . " ({$report['completed_count']} dari {$report['total_count']} subdomain selesai dinilai)",
                ] as $label => $value)
                    <tr>
                        <td class="w-40 py-0.5 pr-3 text-gray-500">{{ $label }}</td>
                        <td class="py-0.5">: {{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @unless ($report['is_final'])
            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Laporan sementara: masih ada subdomain yang belum selesai dinilai. Nilai kematangan domain dihitung dari subdomain yang sudah selesai.
            </div>
        @endunless

        {{-- 1. Ringkasan kematangan domain --}}
        <h3 class="mb-3 mt-8 text-base font-bold">1. Nilai Kematangan Domain Service Operation</h3>
        <div class="avoid-break grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500">Nilai Kematangan Domain</p>
                <p class="mt-1 text-4xl font-bold">{{ \App\Support\Itil::formatMaturity($report['index']) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500">Maturity Level</p>
                <p class="mt-1 text-4xl font-bold">{{ $report['maturity_level'] !== null ? 'Level ' . $report['maturity_level'] : '-' }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500">Keterangan</p>
                <p class="mt-2 text-lg font-bold">{{ $report['maturity_name'] }}</p>
                <p class="text-xs text-gray-500">{{ \App\Support\Itil::levelLabel($report['maturity_level']) }}</p>
            </div>
        </div>

        {{-- 2. Hasil & gap analysis per subdomain --}}
        <h3 class="mb-3 mt-8 text-base font-bold">2. Hasil Maturity Level dan Gap Analysis per Subdomain</h3>
        <div class="avoid-break overflow-x-auto rounded-lg border border-gray-200">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12">No</th>
                        <th>Subdomain</th>
                        <th class="text-center">Current Level</th>
                        <th class="text-center">Target Level</th>
                        <th class="text-center">Gap</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td><span class="font-semibold">{{ $row['code'] }}</span> &middot; {{ $row['name'] }}</td>
                            <td class="text-center">{{ $row['completed'] ? $row['level'] : '-' }}</td>
                            <td class="text-center">{{ $row['target'] }}</td>
                            <td class="text-center font-semibold">{{ $row['gap'] ?? '-' }}</td>
                            <td>
                                @if ($row['completed'])
                                    {{ \App\Support\Itil::levelName($row['level']) }}
                                @else
                                    <span class="badge badge-{{ $row['status_color'] }}">{{ $row['status_label'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-2 text-xs text-gray-500">Gap = Target Level − Current Level (Rumus 2.4).</p>

        {{-- 3. Perhitungan kematangan domain (Tabel 3.60) --}}
        <h3 class="mb-3 mt-8 text-base font-bold">3. Perhitungan Nilai Kematangan Domain</h3>
        <div class="avoid-break grid gap-5 md:grid-cols-2">
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">No</th>
                            <th>Kode Subdomain</th>
                            <th class="text-center">Nilai Maturity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows->where('completed', true)->values() as $row)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $row['code'] }}</td>
                                <td class="text-center">{{ $row['level'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-xs italic text-gray-400">Belum ada subdomain yang selesai dinilai.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="2">Total Nilai Maturity</td>
                            <td class="text-center">{{ $report['sum'] }}</td>
                        </tr>
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="2">Jumlah Subdomain</td>
                            <td class="text-center">{{ $report['completed_count'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="rounded-lg border border-gray-200 p-5 text-sm">
                <p class="text-gray-500">Rumus 2.3</p>
                <p class="mt-1 font-medium">Kematangan Domain = Σ Nilai Subdomain ÷ Σ Jumlah Subdomain</p>
                @if ($report['completed_count'])
                    <p class="mt-3">= {{ $report['sum'] }} ÷ {{ $report['completed_count'] }}</p>
                    <p class="text-lg font-bold">= {{ \App\Support\Itil::formatMaturity($report['index']) }} &rarr; Level {{ $report['maturity_level'] }} ({{ $report['maturity_name'] }})</p>
                @else
                    <p class="mt-3 text-gray-400">Belum ada subdomain yang selesai dinilai.</p>
                @endif

                <table class="mt-4 w-full text-xs">
                    <thead class="text-gray-500">
                        <tr><th class="py-1 text-left">Interval</th><th class="py-1 text-left">Level</th></tr>
                    </thead>
                    <tbody>
                        @php
                            $previous = 0;
                        @endphp
                        @foreach (config('itil.maturity_scale') as $range)
                            <tr @class(['font-semibold text-brand-700' => $report['maturity_level'] === $range['level']])>
                                <td class="py-0.5">{{ number_format($previous ? $previous + 0.01 : 0, 2, ',', '') }} – {{ number_format($range['max'], 2, ',', '') }}</td>
                                <td class="py-0.5">Level {{ $range['level'] }} &middot; {{ config("itil.levels.{$range['level']}.name") }}</td>
                            </tr>
                            @php
                                $previous = $range['max'];
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Hasil pengukuran per subdomain --}}
        <h3 class="mb-3 mt-8 text-base font-bold">4. Hasil Pengukuran Level per Subdomain</h3>
        <div class="space-y-6">
            @foreach ($rows as $row)
                <div class="avoid-break rounded-lg border border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="font-semibold">{{ $row['code'] }} &middot; {{ $row['name'] }}</p>
                        <p class="text-sm">
                            @if ($row['completed'])
                                Hasil <strong>Level {{ $row['level'] }}</strong> ({{ \App\Support\Itil::levelName($row['level']) }})
                            @else
                                <span class="badge badge-{{ $row['status_color'] }}">{{ $row['status_label'] }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Rating per atribut (format Tabel 3.50 - 3.58) --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-center text-xs">
                            <thead class="text-gray-500">
                                <tr class="border-b border-gray-100">
                                    <th rowspan="2" class="px-3 py-2 text-left">Atribut</th>
                                    @foreach ($attributesByLevel as $level => $items)
                                        <th colspan="{{ $items->count() }}" class="border-l border-gray-100 px-2 py-1.5 font-semibold">{{ $levelLabels[$level] ?? "Level {$level}" }}</th>
                                    @endforeach
                                </tr>
                                <tr class="border-b border-gray-100">
                                    @foreach ($attributes as $attribute)
                                        <th class="border-l border-gray-100 px-2 py-1.5 font-semibold" title="{{ $attribute->name }}">{{ $attribute->code }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="px-3 py-2 text-left text-gray-500">Nilai</td>
                                    @foreach ($attributes as $attribute)
                                        @php
                                            $score = $row['history'][$attribute->level]['attributes'][$attribute->code] ?? null;
                                        @endphp
                                        <td class="border-l border-gray-100 px-2 py-2">{{ $score ? \App\Support\Itil::formatPercent($score['percent']) : '-' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 text-left text-gray-500">Rating</td>
                                    @foreach ($attributes as $attribute)
                                        @php
                                            $score = $row['history'][$attribute->level]['attributes'][$attribute->code] ?? null;
                                        @endphp
                                        <td class="border-l border-gray-100 px-2 py-2">
                                            @if ($score)
                                                <span class="badge badge-{{ config("itil.ratings.{$score['rating']}.color") }}">{{ $score['rating'] }}</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Nilai per level (format Tabel 3.59) --}}
                    @php
                        $assessed = collect($row['history'])->filter(fn ($h) => isset($h['rating']));
                    @endphp
                    @if ($assessed->isNotEmpty())
                        <div class="overflow-x-auto border-t border-gray-100">
                            <table class="table text-xs">
                                <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th class="text-center">Skor</th>
                                        <th class="text-center">Jumlah Pertanyaan</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Rating</th>
                                        <th>Keputusan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assessed as $level => $history)
                                        <tr>
                                            <td class="font-medium">{{ $levelLabels[$level] }}</td>
                                            <td class="text-center">{{ $history['score'] + 0 }}</td>
                                            <td class="text-center">{{ $history['total'] }}</td>
                                            <td class="text-center font-semibold">{{ \App\Support\Itil::formatPercent($history['percent']) }}</td>
                                            <td class="text-center"><span class="badge badge-{{ config("itil.ratings.{$history['rating']}.color") }}">{{ $history['rating'] }}</span></td>
                                            <td>{{ \App\Services\MaturityAssessor::decisionText($level, $history['rating'], $level < $max) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- 5. Rincian jawaban (lembar kerja) --}}
        <div id="detail-section" class="hidden">
            <h3 class="mb-3 mt-8 text-base font-bold">5. Rincian Jawaban Lembar Kerja</h3>
            <p class="mb-4 text-xs text-gray-500">Nilai jawaban: Y (Ada) = 1, P (Sebagian) = 0,5, N (Tidak) = 0.</p>

            @foreach ($rows as $row)
                @continue($row['answers']->isEmpty())
                <p class="mb-2 mt-5 font-semibold">{{ $row['code'] }} &middot; {{ $row['name'] }}</p>

                @foreach ($row['answers'] as $code => $group)
                    <div class="mb-4 overflow-x-auto rounded-lg border border-gray-200">
                        <p class="border-b border-gray-100 bg-gray-50 px-4 py-2 text-xs font-semibold">
                            {{ $group['attribute']->name }} ({{ $code }}) &middot; {{ $group['attribute']->level_label }}
                        </p>
                        <table class="table text-xs">
                            <thead>
                                <tr>
                                    <th class="w-10">No</th>
                                    <th>Pertanyaan</th>
                                    <th class="w-10 text-center">Y</th>
                                    <th class="w-10 text-center">P</th>
                                    <th class="w-10 text-center">N</th>
                                    <th class="w-12 text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group['items'] as $entry)
                                    <tr class="align-top">
                                        <td>{{ $entry['question']->number }}.</td>
                                        <td>{{ $entry['question']->question_text }}</td>
                                        @foreach (['Y', 'P', 'N'] as $value)
                                            <td class="text-center">{!! $entry['answer']->answer === $value ? '&#10003;' : '' !!}</td>
                                        @endforeach
                                        <td class="text-center">{{ $entry['answer']->score + 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endforeach
        </div>

        {{-- Tanda tangan --}}
        <div class="avoid-break mt-12 grid grid-cols-2 gap-8 text-center text-sm">
            <div></div>
            <div>
                <p>Surabaya, {{ now()->translatedFormat('d F Y') }}</p>
                <p>Asesor,</p>
                <p class="mt-16 font-semibold underline">{{ $assessment->asesor->name }}</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tampilkan / sembunyikan rincian jawaban (ikut berlaku saat dicetak)
        document.getElementById('toggle-detail').addEventListener('change', function () {
            document.getElementById('detail-section').classList.toggle('hidden', !this.checked);
        });
    </script>
@endpush
