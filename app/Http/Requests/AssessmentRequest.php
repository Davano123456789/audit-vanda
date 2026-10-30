<?php

namespace App\Http\Requests;

use App\Models\AssessmentProcess;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $assessment = $this->route('assessment');

        // Subdomain yang sudah mulai dinilai selalu tetap ikut, walau checkbox-nya dinonaktifkan
        if ($assessment) {
            $locked = $assessment->processes()->where('status', '!=', 'not_started')->pluck('process_code');
            $this->merge(['processes' => collect($this->processes)->merge($locked)->unique()->values()->all()]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'asesor_id' => ['required', Rule::exists('users', 'id')->where('role', User::ROLE_ASESOR)],
            'processes' => ['required', 'array', 'min:1'],
            'processes.*' => ['distinct', 'exists:itil_processes,code'],
            'target_levels' => ['nullable', 'array'],
            'target_levels.*' => ['integer', Rule::in(AssessmentProcess::TARGET_LEVELS)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama assessment',
            'company_name' => 'nama perusahaan',
            'asesor_id' => 'asesor',
            'processes' => 'subdomain',
            'target_levels.*' => 'target level',
        ];
    }

    public function messages(): array
    {
        return [
            'processes.required' => 'Pilih minimal 1 subdomain yang akan dinilai.',
        ];
    }

    public function assessmentData(): array
    {
        return $this->safe()->only(['name', 'company_name', 'description', 'asesor_id']);
    }

    /**
     * Target level untuk sebuah subdomain, default 3 jika tidak diisi.
     */
    public function targetLevelFor(string $processCode): int
    {
        return (int) ($this->input("target_levels.{$processCode}") ?: 3);
    }
}
