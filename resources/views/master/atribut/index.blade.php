@extends('layouts.master')

@section('title', 'Process Attribute')

@section('content')
    @php
        $levelLabels = \App\Models\ProcessAttribute::LEVEL_LABELS;
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Process Attribute</h2>
            <p class="mt-1 text-sm text-gray-500">Atribut proses yang dinilai pada setiap level kematangan.</p>
        </div>
        <a href="{{ route('master.atribut.create') }}" class="btn btn-primary"><i data-lucide="plus" class="h-4 w-4"></i> Create</a>
    </div>

    {{-- Pencarian & filter --}}
    <div class="card mb-6">
        <form method="GET" class="flex flex-col gap-3 p-4 sm:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama atribut..." class="input sm:flex-1">
            <select name="level" class="input sm:w-40">
                <option value="">Semua level</option>
                @foreach ($levelLabels as $value => $label)
                    <option value="{{ $value }}" @selected((int) request('level') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
                @if (request()->hasAny(['q', 'level']))
                    <a href="{{ route('master.atribut.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>
        <p class="border-t border-gray-100 px-4 py-3 text-xs text-gray-500">
            Penilaian dimulai dari Level 1 &amp; 2. Jika nilai seluruh atribut pada satu level mencapai rating
            <strong>F (≥ 85%)</strong>, penilaian dilanjutkan ke level berikutnya. Jika Level 1 &amp; 2 tidak mencapai F, hasilnya Level 0.
        </p>
    </div>

    {{-- Tabel per level --}}
    @forelse ($attributesByLevel as $level => $attributes)
        <div class="card mb-4">
            <div class="card-header py-3">
                <div class="flex items-center gap-2">
                    <span class="badge badge-brand">{{ $levelLabels[$level] ?? "Level {$level}" }}</span>
                    <span class="text-sm text-gray-500">{{ $attributes->count() }} atribut</span>
                </div>
                <a href="{{ route('master.atribut.create', ['level' => $level]) }}" class="inline-flex items-center gap-1 text-xs font-medium text-brand-700 hover:underline">
                    <i data-lucide="plus" class="h-3.5 w-3.5"></i> Tambah di level ini
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-16">Urutan</th>
                            <th class="w-24">Kode</th>
                            <th>Nama Atribut</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Pertanyaan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attributes as $attribute)
                            <tr>
                                <td class="text-gray-500">{{ $attribute->sort_order }}</td>
                                <td class="font-semibold text-brand-700">{{ $attribute->code }}</td>
                                <td class="font-medium">{{ $attribute->name }}</td>
                                <td class="max-w-md text-gray-500">{{ Str::limit($attribute->description, 100) ?: '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('master.pertanyaan.index', ['atribut' => $attribute->code]) }}" class="font-medium text-brand-700 hover:underline">{{ $attribute->questions_count }}</a>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('master.atribut.edit', $attribute) }}" class="btn-icon" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                        <form method="POST" action="{{ route('master.atribut.destroy', $attribute) }}" data-confirm="Hapus atribut {{ $attribute->code }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon-danger" title="Hapus"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card py-12 text-center text-sm text-gray-500">Tidak ada process attribute yang ditemukan.</div>
    @endforelse
@endsection
