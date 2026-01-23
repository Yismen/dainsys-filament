<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'name' => $this->name,
            'project_id' => optional($this->project)->id,
            'project' => $this->project->name,
            'source_id' => optional($this->source)->id,
            'source' => $this->source->name,
            'revenue_type' => $this->revenue_type,
            'sph_goal' => $this->sph_goal,
            'rate' => $this->revenue_rate,
        ];
    }
}
