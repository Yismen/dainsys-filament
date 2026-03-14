<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollHourResource extends JsonResource
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
            'date' => $this->date,
            'total_hours' => $this->total_hours,
            'regular_hours' => $this->regular_hours,
            'overtime_hours' => $this->overtime_hours,
            'holiday_hours' => $this->holiday_hours,
            'seventh_day_hours' => $this->seventh_day_hours,
            'week_ending_at' => $this->week_ending_at,
            'payroll_ending_at' => $this->payroll_ending_at,
            'is_sunday' => $this->is_sunday,
            'is_holiday' => $this->is_holiday,
        ];
    }
}
