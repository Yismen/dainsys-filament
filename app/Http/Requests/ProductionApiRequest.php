<?php

namespace App\Http\Requests;

use App\Services\DateFilterRangeService;
use Illuminate\Foundation\Http\FormRequest;

class ProductionApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    try {
                        app(DateFilterRangeService::class)->resolve((string) $value);
                    } catch (\Throwable) {
                        $fail("The {$attribute} must be a valid date, date range, or fixed value in the format last_N_days.");
                    }
                },
            ],
        ];
    }
}
