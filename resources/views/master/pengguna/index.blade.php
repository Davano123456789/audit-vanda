@extends('layouts.master')

@section('title', 'Pengguna')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-bold">Pengguna</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola akun administrator dan asesor yang dapat mengakses sistem.</p>
        </div>
        <a href="{{ route('master.pengguna.create') }}" class="btn btn-primary"><i data-lucide="user-plus" class="h-4 w-4"></i> Create</a>
    </div>

    <div class="card">
        {{-- Pencarian & filter --}}
        <form method="GET" class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..." class="input sm:flex-1">
            <select name="role" class="input sm:w-44">
                <option value="">Semua role</option>
                @foreach ($roleOptions as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
                @if (request()->hasAny(['q', 'role']))
                    <a href="{{ route('master.pengguna.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Telepon</th>
                        <th class="text-center">Assessment</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-semibold text-brand-700">{{ $user->initials }}</div>
                                    <div class="min-w-0">
                                        <p class="font-medium">
                                            {{ $user->name }}
                                            @if ($user->is(auth()->user()))
                                                <span class="text-xs font-normal text-gray-400">(Anda)</span>
                                            @endif
                                        </p>
                                        <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge {{ $user->isAdmin() ? 'badge-amber' : 'badge-blue' }}">{{ $user->role_label }}</span></td>
                            <td class="text-gray-600">{{ $user->phone ?: '-' }}</td>
                            <td class="text-center">{{ $user->isAsesor() ? $user->assessments_count : '-' }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('master.pengguna.edit', $user) }}" class="btn-icon" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('master.pengguna.destroy', $user) }}" data-confirm="Hapus pengguna {{ $user->name }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm text-gray-500">Tidak ada pengguna yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-gray-100 p-4">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
