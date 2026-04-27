<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'personal_id_type' => $this->personal_id_type,
            'personal_id' => $this->personal_id,
            'internal_id' => $this->internal_id,
            'hired_at' => $this->hired_at?->format('Y-m-d'),
            'site_id' => $this->site_id,
            'site' => $this->site?->name,
            'project_id' => $this->project_id,
            'project' => $this->project?->name,
            'supervisor_id' => $this->supervisor_id,
            'supervisor' => $this->supervisor?->name,
            'position_id' => $this->position_id,
            'position' => $this->position?->name,
            'hourly_rate' => $this->position?->hourly_rate,
            'status' => $this->status,
            'bank_name' => $this->bankAccount?->bank?->name,
            'bank_account_number' => $this->bankAccount?->account,
            'is_universal' => $this->universal ? true : false,
        ];
    }
}
