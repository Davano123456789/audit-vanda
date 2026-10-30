@extends('layouts.master')

@section('title', 'Penilaian ' . $item->process_code)

@section('content')
    @php
        $level = $result['active_level'];
        $levelLabels = \App\Models\ProcessAttribute::LEVEL_LABELS;
        $choices = [
            'Y' => ['Ada', 'peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white'],
            'P' => ['Sebagian', 'peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-checked:text-white'],
            'N' => ['Tidak', 'peer-checked:border-red-500 peer-checked:bg-red-500 peer-checked:text-white'],
        ];
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">{{ $item->process_code }} &middot; {{ $item->process->name }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $assessment->name }} &mdash; target Level {{ $item->target_level }}</p>
        </div>
        <a href="{{ route('penilaian.show', $assessment) }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
    </div>

    {{-- Tahapan level --}}
    <div class="card mb-6 flex flex-wrap gap-2 p-3">
        @foreach (\App\Models\ProcessAttribute::LEVELS as $step)
            @php
                $history = $result['history'][$step] ?? null;
                [$stateClass, $icon, $note] = match (true) {
                    $step === $level => ['border-brand-500 bg-brand-50 text-brand-800', 'pencil-line', 'Sedang dinilai'],
                    ($history['status'] ?? null) === 'passed' => ['border-emerald-200 bg-emerald-50 text-emerald-800', 'check-circle-2', \App\Support\Itil::formatPercent($history['percent']) . ' · F'],
                    ($history['status'] ?? null) === 'skipped' || ! in_array($step, $result['levels']) => ['border-gray-200 bg-gray-50 text-gray-400', 'minus-circle', 'Tidak ada pertanyaan'],
                    default => ['border-gray-200 bg-white text-gray-400', 'lock', 'Terkunci'],
                };
            @endphp
            <div class="flex min-w-[150px] flex-1 items-center gap-3 rounded-lg border px-4 py-2.5 {{ $stateClass }}">
                <i data-lucide="{{ $icon }}" class="h-5 w-5 shrink-0"></i>
                <div>
                    <p class="text-sm font-semibold">{{ $levelLabels[$step] }}</p>
                    <p class="text-xs">{{ $note }}</p>
                </div>
            </div>
        @endforeach
    </div>


    <form method="POST" action="{{ route('penilaian.simpan', [$assessment, $item->process_code]) }}" id="assessment-form" novalidate>
        @csrf
        <input type="hidden" name="level" value="{{ $level }}">

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Pertanyaan per process attribute --}}
            <div class="space-y-4 lg:col-span-2">
                @foreach ($questionsByAttribute as $attributeCode => $questions)
                    @php
                        $attribute = $questions->first()->attribute;
                    @endphp
                    <div class="card overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50/70 px-5 py-3">
                            <p class="text-sm font-semibold">
                                <span class="text-brand-700">{{ $attribute->code }}</span> &middot; {{ $attribute->name }}
                                <span class="font-normal text-gray-500">({{ $questions->count() }} pertanyaan)</span>
                            </p>
                            @if ($attribute->description)
                                <p class="text-xs text-gray-500">{{ $attribute->description }}</p>
                            @endif
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach ($questions as $question)
                                @php
                                    $answer = $answers->get($question->id);
                                    $selected = old("answers.{$question->id}", $answer?->answer);
                                @endphp
                                <div class="px-5 py-4" data-question>
                                    <div class="flex gap-3">
                                        <span class="w-6 shrink-0 text-sm font-semibold text-gray-400">{{ $question->number }}.</span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm leading-relaxed">{{ $question->question_text }}</p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($choices as $value => [$label, $checkedClass])
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $value }}" class="peer sr-only"
                                                               @checked($selected === $value)>
                                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-50 {{ $checkedClass }}">
                                                            {{ $value }} &middot; {{ $label }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Ringkasan & tombol simpan --}}
            <div>
                <div class="space-y-4 lg:sticky lg:top-20">
                    <div class="card p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="font-semibold">{{ $levelLabels[$level] }}</h3>
                            <span class="badge badge-brand">{{ $total }} pertanyaan</span>
                        </div>

                        <div class="mb-1 flex justify-between text-xs text-gray-500">
                            <span>Terjawab</span>
                            <span><strong id="sum-answered" class="text-ink">0</strong> / {{ $total }}</span>
                        </div>
                        <div class="mb-4 h-2 overflow-hidden rounded-full bg-gray-100">
                            <div id="sum-bar" class="h-full rounded-full bg-brand-500 transition-all" style="width: 0%"></div>
                        </div>

                        <dl class="grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-lg bg-emerald-50 p-2">
                                <dt class="text-[11px] text-gray-500">Y</dt>
                                <dd id="sum-Y" class="text-lg font-bold text-emerald-700">0</dd>
                            </div>
                            <div class="rounded-lg bg-amber-50 p-2">
                                <dt class="text-[11px] text-gray-500">P</dt>
                                <dd id="sum-P" class="text-lg font-bold text-amber-700">0</dd>
                            </div>
                            <div class="rounded-lg bg-red-50 p-2">
                                <dt class="text-[11px] text-gray-500">N</dt>
                                <dd id="sum-N" class="text-lg font-bold text-red-600">0</dd>
                            </div>
                        </dl>

                        <div class="mt-4 rounded-lg border border-dashed border-gray-300 p-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Skor</span>
                                <span><strong id="sum-score">0</strong> / {{ $total }}</span>
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Nilai</span>
                                <strong id="sum-percent">0%</strong>
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Rating</span>
                                <strong id="sum-rating">-</strong>
                            </div>
                            <p id="sum-decision" class="mt-2 text-xs text-gray-600">Jawab semua pertanyaan untuk melihat hasil level ini.</p>
                        </div>

                        <div class="mt-3 grid grid-cols-3 gap-2 text-xs">
                            @foreach (['Y', 'P', 'N'] as $value)
                                <button type="button" data-fill="{{ $value }}"
                                        class="rounded-md border border-gray-200 py-1.5 text-gray-600 hover:bg-gray-50">Semua {{ $value }}</button>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 w-full disabled:cursor-not-allowed disabled:opacity-50">
                            <i data-lucide="check-check" class="h-4 w-4"></i> Simpan &amp; Evaluasi
                        </button>
                        <p class="mt-2 text-[11px] text-gray-400">Semua pertanyaan wajib dijawab sebelum dievaluasi.</p>
                    </div>

                    {{-- Keterangan nilai & rating --}}
                    <div class="card space-y-3 p-5 text-xs">
                        <div>
                            <h3 class="mb-2 text-sm font-semibold">Nilai Jawaban</h3>
                            <p><strong>Y</strong> (Ada) = 1 &middot; <strong>P</strong> (Sebagian) = 0,5 &middot; <strong>N</strong> (Tidak) = 0</p>
                        </div>
                        <div>
                            <h3 class="mb-2 text-sm font-semibold">Rating Level</h3>
                            <ul class="space-y-1.5">
                                @foreach (config('itil.ratings') as $code => $rating)
                                    <li class="flex items-center gap-2">
                                        <span class="badge badge-{{ $rating['color'] }} w-7 justify-center">{{ $code }}</span>
                                        <span class="flex-1">{{ $rating['name'] }}</span>
                                        <span class="text-gray-500">{{ $rating['range'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="text-gray-500">Nilai = Σ nilai jawaban ÷ Σ pertanyaan × 100%. Rating F lanjut ke level berikutnya, selain itu penilaian berhenti.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if ($item->status === 'in_progress')
        <form method="POST" action="{{ route('penilaian.reset', [$assessment, $item->process_code]) }}" class="mt-6 text-right"
              data-confirm="Reset penilaian {{ $item->process_code }}?"
              data-confirm-text="Semua jawaban subdomain ini akan dihapus, penilaian dimulai lagi dari Level 1 & 2."
              data-confirm-button="Ya, reset">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:underline">
                <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i> Reset penilaian subdomain ini
            </button>
        </form>
    @endif
@endsection

@push('scripts')
    <script>
        // Ringkasan nilai level aktif yang diperbarui setiap kali jawaban dipilih
        (function () {
            const form = document.getElementById('assessment-form');
            const total = {{ $total }};
            const level = {{ $level }};
            const startLevel = {{ config('itil.start_level') }};
            const hasNext = @json($result['has_next_level']);
            const scores = @json(\App\Models\Answer::SCORES);
            const ratings = @json(config('itil.ratings'));
            const levelLabels = @json($levelLabels);

            const rating = (percent) => {
                for (const [code, r] of Object.entries(ratings)) {
                    if (r.inclusive ? percent >= r.min : percent > r.min) return code;
                }
                return 'N';
            };

            // Sama dengan MaturityAssessor::resultLevel()
            const resultLevel = (code) => {
                if (code === 'F') return level;
                if (level === startLevel) return 0;
                return level - 1;
            };

            const nextLabel = () => {
                const next = Object.keys(levelLabels).map(Number).find(l => l > level);
                return levelLabels[next] ?? 'level berikutnya';
            };

            const update = () => {
                const checked = [...form.querySelectorAll('input[type=radio]:checked')];
                const counts = { Y: 0, P: 0, N: 0 };
                checked.forEach(input => counts[input.value]++);

                const score = checked.reduce((sum, input) => sum + scores[input.value], 0);
                const percent = total ? Math.round(score / total * 10000) / 100 : 0;

                document.getElementById('sum-answered').textContent = checked.length;
                document.getElementById('sum-bar').style.width = (checked.length / total * 100) + '%';
                ['Y', 'P', 'N'].forEach(code => document.getElementById('sum-' + code).textContent = counts[code]);
                document.getElementById('sum-score').textContent = String(score).replace('.', ',');
                document.getElementById('sum-percent').textContent = String(percent).replace('.', ',') + '%';

                const decision = document.getElementById('sum-decision');
                const ratingEl = document.getElementById('sum-rating');

                if (checked.length < total) {
                    ratingEl.textContent = '-';
                    decision.textContent = `Masih ${total - checked.length} pertanyaan belum dijawab.`;
                    return;
                }

                const code = rating(percent);
                ratingEl.textContent = `${code} · ${ratings[code].name}`;
                decision.textContent = code === 'F'
                    ? (hasNext ? `Lulus, lanjut ke ${nextLabel()}.` : `Lulus, hasil Level ${level}.`)
                    : `Berhenti, hasil Level ${resultLevel(code)}.`;
            };

            form.addEventListener('change', update);

            // Tombol "Semua Y / P / N"
            document.querySelectorAll('[data-fill]').forEach(button => button.addEventListener('click', () => {
                form.querySelectorAll(`input[type=radio][value="${button.dataset.fill}"]`).forEach(input => input.checked = true);
                update();
            }));

            update();
        })();
    </script>
@endpush
