@extends('layouts.master')

@section('title', 'Question')

@section('content')
    @php
        $levelLabels = \App\Models\ProcessAttribute::LEVEL_LABELS;
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Question</h2>
            <p class="mt-1 text-sm text-gray-500">Pertanyaan lembar kerja yang dijawab Y / P / N oleh asesor pada setiap process attribute.</p>
        </div>
        <a href="{{ route('master.pertanyaan.create', request()->only('proses', 'atribut')) }}" class="btn btn-primary"><i data-lucide="plus" class="h-4 w-4"></i> Create</a>
    </div>

    {{-- Ringkasan jumlah pertanyaan per subdomain dan atribut (angka bisa diklik untuk memfilter) --}}
    <div class="card mb-6">
        <div class="card-header py-3">
            <h3 class="flex items-center gap-2 text-sm font-semibold"><i data-lucide="grid-3x3" class="h-4 w-4 text-brand-600"></i> Ringkasan Jumlah Pertanyaan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="text-xs text-gray-500">
                    <tr class="border-b border-gray-100">
                        <th rowspan="2" class="px-4 py-2 text-left font-semibold uppercase">Subdomain</th>
                        @foreach ($attributes->groupBy('level') as $level => $items)
                            <th colspan="{{ $items->count() }}" class="border-l border-gray-100 px-2 py-2 font-semibold">{{ $levelLabels[$level] ?? "Level {$level}" }}</th>
                        @endforeach
                        <th rowspan="2" class="border-l border-gray-100 px-4 py-2 font-semibold uppercase">Total</th>
                    </tr>
                    <tr class="border-b border-gray-100">
                        @foreach ($attributes as $attribute)
                            <th class="px-2 py-2 font-semibold" title="{{ $attribute->name }}">{{ $attribute->code }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($processes as $process)
                        @php
                            $counts = $summary[$process->code] ?? [];
                        @endphp
                        <tr @class(['bg-brand-50/60' => request('proses') === $process->code])>
                            <td class="px-4 py-2 text-left">
                                <span class="font-semibold">{{ $process->code }}</span>
                                <span class="hidden text-gray-500 md:inline">&middot; {{ $process->name }}</span>
                            </td>
                            @foreach ($attributes as $attribute)
                                <td class="px-2 py-2">
                                    @if ($count = $counts[$attribute->code] ?? 0)
                                        <a href="{{ route('master.pertanyaan.index', ['proses' => $process->code, 'atribut' => $attribute->code]) }}"
                                           class="font-medium text-brand-700 hover:underline">{{ $count }}</a>
                                    @else
                                        <span class="text-gray-300">&ndash;</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="border-l border-gray-100 px-4 py-2">
                                <a href="{{ route('master.pertanyaan.index', ['proses' => $process->code]) }}" class="font-semibold hover:underline">{{ array_sum($counts) }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        {{-- Pencarian & filter --}}
        <form method="GET" class="flex flex-col gap-3 border-b border-gray-100 p-4 lg:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari teks pertanyaan..." class="input lg:flex-1">
            <select name="proses" class="input lg:w-56">
                <option value="">Semua process</option>
                @foreach ($processes as $process)
                    <option value="{{ $process->code }}" @selected(request('proses') === $process->code)>{{ $process->code }} - {{ $process->name }}</option>
                @endforeach
            </select>
            <select name="atribut" class="input lg:w-56">
                <option value="">Semua atribut</option>
                @foreach ($attributes as $attribute)
                    <option value="{{ $attribute->code }}" @selected(request('atribut') === $attribute->code)>{{ $attribute->code }} - {{ $attribute->name }}</option>
                @endforeach
            </select>
            <select name="level" class="input lg:w-36">
                <option value="">Semua level</option>
                @foreach ($levelLabels as $value => $label)
                    <option value="{{ $value }}" @selected((int) request('level') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
                @if (request()->hasAny(['q', 'proses', 'atribut', 'level']))
                    <a href="{{ route('master.pertanyaan.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-20">Process</th>
                        <th class="w-44">Process Attribute</th>
                        <th class="w-12">No</th>
                        <th>Pertanyaan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($questions as $question)
                        <tr class="align-top">
                            <td><span class="badge badge-brand">{{ $question->process_code }}</span></td>
                            <td>
                                <p class="font-medium">{{ $question->attribute->name }}</p>
                                <p class="text-xs text-gray-500">{{ $question->attribute->code }} &middot; {{ $question->attribute->level_label }}</p>
                            </td>
                            <td class="text-gray-500">{{ $question->number }}.</td>
                            <td class="leading-relaxed">{{ $question->question_text }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('master.pertanyaan.edit', $question) }}" class="btn-icon" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('master.pertanyaan.destroy', $question) }}" data-confirm="Hapus pertanyaan ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm text-gray-500">Tidak ada pertanyaan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">Total {{ $questions->total() }} pertanyaan</p>
            @if ($questions->hasPages())
                <div>{{ $questions->links() }}</div>
            @endif
        </div>
    </div>
@endsection
