<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncentiveResource extends JsonResource
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
            'project_id' => $this->project_id,
            'project_name' => $this->project?->name,
            'payable_date' => $this->payable_date,
            'amount' => $this->amount,
            'total_production_hours' => $this->total_production_hours,
            'total_sales' => $this->total_sales,
        ];
    }
}
