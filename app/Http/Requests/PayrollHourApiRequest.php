<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PayrollHourApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['nullable', 'regex:/^\\d{4}-\\d{2}-\\d{2}(,\\d{4}-\\d{2}-\\d{2})?$/'],
            'week_ending_at' => ['nullable', 'regex:/^\\d{4}-\\d{2}-\\d{2}(,\\d{4}-\\d{2}-\\d{2})?$/'],
            'payroll_ending_at' => ['nullable', 'regex:/^\\d{4}-\\d{2}-\\d{2}(,\\d{4}-\\d{2}-\\d{2})?$/'],
        ];
    }
}
