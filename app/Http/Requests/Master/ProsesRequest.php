<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProsesRequest extends FormRequest
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
            'domain_code' => ['required', 'exists:itil_domains,code'],
            'code' => [
                'required', 'string', 'max:10', 'regex:/^[A-Z]+$/',
                Rule::unique('itil_processes', 'code')->ignore($this->route('proses')),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'domain_code' => 'domain',
            'code' => 'kode process',
            'name' => 'nama process',
        ];
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Kode process hanya boleh huruf, contoh: IM.',
        ];
    }
}
