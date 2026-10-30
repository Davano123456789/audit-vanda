@extends('layouts.master')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Tambah Pengguna</h2>
        <p class="mt-1 text-sm text-gray-500">Buat akun administrator atau asesor baru.</p>
    </div>

    <form method="POST" action="{{ route('master.pengguna.store') }}" class="card" novalidate>
        @csrf

        <div class="space-y-6 p-6">
            {{-- Data akun --}}
            <div>
                <h3 class="mb-4 text-sm font-semibold text-gray-700">Data Akun</h3>
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="name" class="label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input id="name" name="name" value="{{ old('name') }}" class="input @error('name') input-error @enderror">
                        @error('name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="label">Email <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="off" class="input @error('email') input-error @enderror">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="role" class="label">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="input @error('role') input-error @enderror">
                            @foreach ($roleOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', $pengguna->role) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
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
                        <input id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="input @error('phone') input-error @enderror">
                        @error('phone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="label">Alamat</label>
                        <textarea id="address" name="address" rows="2" class="input">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="mb-1 text-sm font-semibold text-gray-700">Password</h3>
                <p class="mb-4 text-xs text-gray-500">Minimal 8 karakter.</p>
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="password" class="label">Password <span class="text-red-500">*</span></label>
                        <input id="password" name="password" type="password" autocomplete="new-password" class="input @error('password') input-error @enderror">
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Ulangi Password <span class="text-red-500">*</span></label>
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
