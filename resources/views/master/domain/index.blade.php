@extends('layouts.master')

@section('title', 'Domain')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Domain ITIL V3</h2>
            <p class="mt-1 text-sm text-gray-500">Tahapan siklus hidup layanan ITIL V3 yang menjadi ruang lingkup penilaian.</p>
        </div>
        <a href="{{ route('master.domain.create') }}" class="btn btn-primary"><i data-lucide="plus" class="h-4 w-4"></i> Create</a>
    </div>

    <div class="card">
        {{-- Pencarian --}}
        <form method="GET" class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama domain..." class="input sm:flex-1">
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
                @if (request()->filled('q'))
                    <a href="{{ route('master.domain.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Domain</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Process</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($domains as $domain)
                        <tr>
                            <td><span class="badge badge-brand">{{ $domain->code }}</span></td>
                            <td class="font-medium">{{ $domain->name }}</td>
                            <td class="max-w-md text-gray-500">{{ Str::limit($domain->description, 110) ?: '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('master.proses.index', ['domain' => $domain->code]) }}" class="font-medium text-brand-700 hover:underline">{{ $domain->processes_count }}</a>
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('master.domain.edit', $domain) }}" class="btn-icon" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('master.domain.destroy', $domain) }}" data-confirm="Hapus domain {{ $domain->code }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm text-gray-500">Belum ada domain.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($domains->hasPages())
            <div class="border-t border-gray-100 p-4">{{ $domains->links() }}</div>
        @endif
    </div>
@endsection
