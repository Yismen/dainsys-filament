<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionResource extends JsonResource
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
            'unique_id' => $this->unique_id,
            'date' => $this->date,
            'employee_id' => $this->employee_id,
            'employee_full_name' => $this->employee->full_name,
            'campaign_id' => $this->campaign_id,
            'campaign_name' => $this->campaign->name,
            'project_id' => $this->campaign->project_id,
            'project_name' => $this->campaign->project->name,
            'client_id' => $this->campaign->project->client_id,
            'client_name' => $this->campaign->project->client->name,
            'site_id' => $this->employee?->site?->id,
            'site_name' => $this->employee?->site?->name,
            'source_id' => $this->campaign->source_id,
            'source_name' => $this->campaign->source->name,
            'supervisor_id' => $this->supervisor_id,
            'supervisor_name' => $this->supervisor?->name,
            'revenue_type' => $this->revenue_type,
            'revenue_rate' => $this->revenue_rate,
            'revenue' => $this->revenue,
            'sph_goal' => $this->sph_goal,
            'conversions' => $this->conversions,
            'total_time' => $this->total_time,
            'production_time' => $this->production_time,
            'talk_time' => $this->talk_time,
            'billable_time' => $this->billable_time,
            ];
    }
}
