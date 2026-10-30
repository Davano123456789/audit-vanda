<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanJawabanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'level' => ['required', 'integer'],
            'answers' => ['nullable', 'array'],
            'answers.*' => ['in:Y,P,N'],
        ];
    }

    public function attributes(): array
    {
        return [
            'answers.*' => 'jawaban',
        ];
    }
}
