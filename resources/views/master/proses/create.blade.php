@extends('layouts.master')

@section('title', 'Create Process Category')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Create Process Category</h2>
        <p class="mt-1 text-sm text-gray-500">Tambahkan subdomain Service Operation baru.</p>
    </div>

    <form method="POST" action="{{ route('master.proses.store') }}" class="card" novalidate>
        @csrf

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label for="domain_code" class="label">Domain <span class="text-red-500">*</span></label>
                    <select id="domain_code" name="domain_code" class="input @error('domain_code') input-error @enderror">
                        <option value="">-- Pilih --</option>
                        @foreach ($domainOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('domain_code', $proses->domain_code) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('domain_code') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="code" class="label">Kode Process <span class="text-red-500">*</span></label>
                    <input id="code" name="code" value="{{ old('code') }}" maxlength="10" placeholder="IM" class="input @error('code') input-error @enderror">
                    <p class="hint">Huruf kapital, contoh: EM, IM, PM, RF, AM.</p>
                    @error('code') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="name" class="label">Name <span class="text-red-500">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" placeholder="Incident Management" class="input @error('name') input-error @enderror">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="input">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
            <a href="{{ route('master.proses.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Save</button>
        </div>
    </form>
@endsection
