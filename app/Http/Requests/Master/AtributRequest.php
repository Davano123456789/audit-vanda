<?php

namespace App\Http\Requests\Master;

use App\Models\ProcessAttribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtributRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['code' => trim((string) $this->code)]);
    }

    public function rules(): array
    {
        return [
            // Huruf besar-kecil dipertahankan, contoh: PDep, PMeas
            'code' => [
                'required', 'string', 'max:10', 'regex:/^[A-Za-z]+$/',
                Rule::unique('process_attributes', 'code')->ignore($this->route('atribut')),
            ],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', Rule::in(ProcessAttribute::LEVELS)],
            'sort_order' => ['required', 'integer', 'min:1', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode atribut',
            'name' => 'nama atribut',
            'sort_order' => 'urutan',
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Kode atribut hanya boleh huruf, contoh: PP atau PDep.',
        ];
    }
}
