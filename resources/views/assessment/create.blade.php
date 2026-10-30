@extends('layouts.master')

@section('title', 'Create Assessment')

@section('content')
    @php
        $checked = old('processes', $domains->flatMap->processes->pluck('code')->all());
    @endphp

    <div class="mb-6">
        <h2 class="text-xl font-bold">Create Assessment</h2>
        <p class="mt-1 text-sm text-gray-500">Buat proyek penilaian baru, pilih asesor, subdomain, dan target level.</p>
    </div>

    <form method="POST" action="{{ route('assessment.store') }}" class="space-y-6" novalidate>
        @csrf

        {{-- Data assessment --}}
        <div class="card">
            <div class="card-header"><h3 class="font-semibold">Data Assessment</h3></div>
            <div class="space-y-5 p-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="label">Nama Assessment <span class="text-red-500">*</span></label>
                        <input id="name" name="name" value="{{ old('name') }}" placeholder="Assessment Service Operation 2026" class="input @error('name') input-error @enderror">
                        @error('name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="company_name" class="label">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input id="company_name" name="company_name" value="{{ old('company_name', $assessment->company_name) }}" class="input @error('company_name') input-error @enderror">
                        @error('company_name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="asesor_id" class="label">Asesor <span class="text-red-500">*</span></label>
                        <select id="asesor_id" name="asesor_id" class="input @error('asesor_id') input-error @enderror">
                            <option value="">-- Pilih asesor --</option>
                            @foreach ($asesorOptions as $id => $name)
                                <option value="{{ $id }}" @selected((int) old('asesor_id') === $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('asesor_id') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="label">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="input">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Subdomain & target level --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="font-semibold">Subdomain yang Dinilai <span class="text-red-500">*</span></h3>
                    <p class="text-xs text-gray-500">Centang subdomain lalu tentukan target level masing-masing.</p>
                </div>
            </div>
            @error('processes') <p class="error-text px-5 pt-3">{{ $message }}</p> @enderror

            @foreach ($domains as $domain)
                <p class="bg-gray-50 px-5 py-2 text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $domain->code }} &middot; {{ $domain->name }}</p>
                <div class="divide-y divide-gray-100">
                    @foreach ($domain->processes as $process)
                        <div class="flex flex-col gap-3 px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="processes[]" value="{{ $process->code }}" @checked(in_array($process->code, $checked))
                                       class="mt-1 h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                <span>
                                    <span class="font-medium"><span class="text-brand-700">{{ $process->code }}</span> &middot; {{ $process->name }}</span>
                                    <span class="block text-xs text-gray-500">{{ $process->questions_count }} pertanyaan</span>
                                </span>
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Target</span>
                                <select name="target_levels[{{ $process->code }}]" class="input w-32">
                                    @foreach (\App\Models\AssessmentProcess::TARGET_LEVELS as $level)
                                        <option value="{{ $level }}" @selected((int) old("target_levels.{$process->code}", 3) === $level)>Level {{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('assessment.index') }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Save</button>
        </div>
    </form>
@endsection
