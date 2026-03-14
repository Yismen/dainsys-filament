<?php

namespace App\Http\Requests;

use App\Services\DateFilterRangeService;
use Illuminate\Foundation\Http\FormRequest;

class PayrollHourApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['nullable', $this->dateFilterRule()],
            'week_ending_at' => ['nullable', $this->dateFilterRule()],
            'payroll_ending_at' => ['nullable', $this->dateFilterRule()],
        ];
    }

    protected function dateFilterRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if ($value === null || $value === '') {
                return;
            }

            try {
                app(DateFilterRangeService::class)->resolve((string) $value);
            } catch (\Throwable) {
                $fail("The {$attribute} must be a valid date, date range, or fixed value in the format last_N_days.");
            }
        };
    }
}
