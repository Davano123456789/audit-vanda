@extends('layouts.master')

@section('title', 'Update Question')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold">Update Question</h2>
        <p class="mt-1 text-sm text-gray-500">{{ $pertanyaan->process_code }} &middot; {{ $pertanyaan->attribute_code }} nomor {{ $pertanyaan->number }}</p>
    </div>

    <form method="POST" action="{{ route('master.pertanyaan.update', $pertanyaan) }}" class="card" novalidate>
        @csrf
        @method('PUT')

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label for="process_code" class="label">Process <span class="text-red-500">*</span></label>
                    <select id="process_code" name="process_code" class="input @error('process_code') input-error @enderror">
                        <option value="">-- Pilih process --</option>
                        @foreach ($processes as $process)
                            <option value="{{ $process->code }}" @selected(old('process_code', $pertanyaan->process_code) === $process->code)>{{ $process->code }} - {{ $process->name }}</option>
                        @endforeach
                    </select>
                    @error('process_code') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="attribute_code" class="label">Process Attribute <span class="text-red-500">*</span></label>
                    <select id="attribute_code" name="attribute_code" class="input @error('attribute_code') input-error @enderror">
                        <option value="">-- Pilih atribut --</option>
                        @foreach ($attributes->groupBy('level') as $level => $items)
                            <optgroup label="{{ \App\Models\ProcessAttribute::LEVEL_LABELS[$level] ?? "Level {$level}" }}">
                                @foreach ($items as $attribute)
                                    <option value="{{ $attribute->code }}" @selected(old('attribute_code', $pertanyaan->attribute_code) === $attribute->code)>{{ $attribute->code }} - {{ $attribute->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('attribute_code') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="number" class="label">Nomor</label>
                    <input id="number" name="number" type="number" min="1" value="{{ old('number', $pertanyaan->number) }}" class="input @error('number') input-error @enderror">
                    @error('number') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="question_text" class="label">Pertanyaan <span class="text-red-500">*</span></label>
                <textarea id="question_text" name="question_text" rows="4" class="input @error('question_text') input-error @enderror">{{ old('question_text', $pertanyaan->question_text) }}</textarea>
                @error('question_text') <p class="error-text">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
            <a href="{{ route('master.pertanyaan.index', ['proses' => $pertanyaan->process_code, 'atribut' => $pertanyaan->attribute_code]) }}" class="btn btn-secondary"><i data-lucide="arrow-left" class="h-4 w-4"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Save</button>
        </div>
    </form>
@endsection
