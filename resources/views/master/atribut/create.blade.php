@extends('layouts.master')

@section('title', 'Create Process Attribute')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Create Process Attribute</h2>
        <p class="mt-1 text-sm text-gray-500">Tambahkan atribut proses yang dinilai pada level tertentu.</p>
    </div>

    <form method="POST" action="{{ route('master.atribut.store') }}" class="card" novalidate>
        @csrf

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-4">
                <div>
                    <label for="code" class="label">Kode Atribut <span class="text-red-500">*</span></label>
                    <input id="code" name="code" value="{{ old('code') }}" maxlength="10" placeholder="WPM" class="input @error('code') input-error @enderror">
                    <p class="hint">Contoh: PP, WPM, PDep.</p>
                    @error('code') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-3">
                    <label for="name" class="label">Nama Atribut <span class="text-red-500">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" placeholder="Work Product Management" class="input @error('name') input-error @enderror">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-4">
                <div>
                    <label for="level" class="label">Level <span class="text-red-500">*</span></label>
                    <select id="level" name="level" class="input @error('level') input-error @enderror">
                        <option value="">-- Pilih level --</option>
                        @foreach (\App\Models\ProcessAttribute::LEVEL_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected((int) old('level', $atribut->level) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('level') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="sort_order" class="label">Urutan <span class="text-red-500">*</span></label>
                    <input id="sort_order" name="sort_order" type="number" min="1" value="{{ old('sort_order', $atribut->sort_order) }}" class="input @error('sort_order') input-error @enderror">
                    <p class="hint">Urutan tampil dalam satu level.</p>
                    @error('sort_order') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="label">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="input">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
            <a href="{{ route('master.atribut.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Save</button>
        </div>
    </form>
@endsection
