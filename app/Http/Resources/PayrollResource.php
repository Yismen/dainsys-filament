<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_full_name' => $this->employee?->full_name,
            'payable_date' => $this->payable_date,
            'total_hours' => $this->total_hours,
            'gross_income' => $this->gross_income,
            'nightly_incomes' => $this->nightly_incomes,
            'overtime_incomes' => $this->overtime_incomes,
            'holiday_incomes' => $this->holiday_incomes,
            'additional_incentives_2' => $this->additional_incentives_2,
            'additional_incentives_1' => $this->additional_incentives_1,
        ];
    }
}
