@extends('layouts.master')

@section('title', 'Edit Pengguna')

@section('content')
    @php
        $isSelf = $pengguna->is(auth()->user());
    @endphp

    <div class="mb-6">
        <h2 class="text-xl font-bold">Edit Pengguna</h2>
        <p class="mt-1 text-sm text-gray-500">{{ $pengguna->name }} &middot; {{ $pengguna->email }}</p>
    </div>

    <form method="POST" action="{{ route('master.pengguna.update', $pengguna) }}" class="card" novalidate>
        @csrf
        @method('PUT')

        <div class="space-y-6 p-6">
            {{-- Data akun --}}
            <div>
                <h3 class="mb-4 text-sm font-semibold text-gray-700">Data Akun</h3>
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="name" class="label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input id="name" name="name" value="{{ old('name', $pengguna->name) }}" class="input @error('name') input-error @enderror">
                        @error('name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="label">Email <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $pengguna->email) }}" autocomplete="off" class="input @error('email') input-error @enderror">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="role" class="label">Role <span class="text-red-500">*</span></label>
                        @if ($isSelf)
                            {{-- Role akun sendiri tidak bisa diubah agar admin tidak terkunci dari menu admin --}}
                            <input type="hidden" name="role" value="{{ $pengguna->role }}">
                            <p class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-600">{{ $pengguna->role_label }}</p>
                            <p class="hint">Role akun Anda sendiri tidak dapat diubah.</p>
                        @else
                            <select id="role" name="role" class="input @error('role') input-error @enderror">
                                @foreach ($roleOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('role', $pengguna->role) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('role') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="mb-4 text-sm font-semibold text-gray-700">Kontak</h3>
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="phone" class="label">Nomor Telepon</label>
                        <input id="phone" name="phone" value="{{ old('phone', $pengguna->phone) }}" placeholder="08xxxxxxxxxx" class="input @error('phone') input-error @enderror">
                        @error('phone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="label">Alamat</label>
                        <textarea id="address" name="address" rows="2" class="input">{{ old('address', $pengguna->address) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="mb-1 text-sm font-semibold text-gray-700">Password</h3>
                <p class="mb-4 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti password.</p>
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="password" class="label">Password Baru</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" class="input @error('password') input-error @enderror">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Ulangi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="input">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
            <a href="{{ route('master.pengguna.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Save</button>
        </div>
    </form>
@endsection
