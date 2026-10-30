@extends('layouts.master')

@section('title', 'Process')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Process Category</h2>
            <p class="mt-1 text-sm text-gray-500">Subdomain Service Operation yang dinilai tingkat kematangannya.</p>
        </div>
        <a href="{{ route('master.proses.create') }}" class="btn btn-primary"><i data-lucide="plus" class="h-4 w-4"></i> Create</a>
    </div>

    <div class="card">
        {{-- Pencarian & filter --}}
        <form method="GET" class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, atau deskripsi process..." class="input sm:flex-1">
            @if (count($domainOptions) > 1)
                <select name="domain" class="input sm:w-56">
                    <option value="">Semua domain</option>
                    @foreach ($domainOptions as $value => $label)
                        <option value="{{ $value }}" @selected(request('domain') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            @endif
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
                @if (request()->hasAny(['q', 'domain']))
                    <a href="{{ route('master.proses.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Process</th>
                        <th>Domain</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Pertanyaan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($processes as $process)
                        <tr>
                            <td><span class="badge badge-brand">{{ $process->code }}</span></td>
                            <td class="font-medium">{{ $process->name }}</td>
                            <td class="text-gray-600">{{ $process->domain->name }}</td>
                            <td class="max-w-md text-gray-500">{{ Str::limit($process->description, 100) ?: '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('master.pertanyaan.index', ['proses' => $process->code]) }}" class="font-medium text-brand-700 hover:underline">{{ $process->questions_count }}</a>
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('master.proses.edit', $process) }}" class="btn-icon" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('master.proses.destroy', $process) }}" data-confirm="Hapus process {{ $process->code }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-sm text-gray-500">Tidak ada process yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($processes->hasPages())
            <div class="border-t border-gray-100 p-4">{{ $processes->links() }}</div>
        @endif
    </div>
@endsection
