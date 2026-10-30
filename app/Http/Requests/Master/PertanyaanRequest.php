<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class PertanyaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'process_code' => ['required', 'exists:itil_processes,code'],
            'attribute_code' => ['required', 'exists:process_attributes,code'],
            'number' => ['nullable', 'integer', 'min:1', 'max:999'],
            'question_text' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'process_code' => 'process',
            'attribute_code' => 'process attribute',
            'number' => 'nomor',
            'question_text' => 'teks pertanyaan',
        ];
    }
}
