@extends('layouts.master')

@section('title', 'Tambah Domain')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Tambah Domain</h2>
        <p class="mt-1 text-sm text-gray-500">Tambahkan domain ITIL V3 baru.</p>
    </div>

    <form method="POST" action="{{ route('master.domain.store') }}" class="card" novalidate>
        @csrf

        <div class="space-y-5 p-6">
            <div class="grid gap-5 sm:grid-cols-3">
                <div>
                    <label for="code" class="label">Kode Domain <span class="text-red-500">*</span></label>
                    <input id="code" name="code" value="{{ old('code') }}" maxlength="10" placeholder="SO" class="input @error('code') input-error @enderror">
                    <p class="hint">Huruf kapital, contoh: SO.</p>
                    @error('code') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="name" class="label">Nama Domain <span class="text-red-500">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" placeholder="Service Operation" class="input @error('name') input-error @enderror">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="input">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
            <a href="{{ route('master.domain.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Save</button>
        </div>
    </form>
@endsection
