<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['code' => strtoupper(trim((string) $this->code))]);
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required', 'string', 'max:10', 'regex:/^[A-Z]+$/',
                Rule::unique('itil_domains', 'code')->ignore($this->route('domain')),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode domain',
            'name' => 'nama domain',
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Kode domain hanya boleh huruf, contoh: SO.',
        ];
    }
}
