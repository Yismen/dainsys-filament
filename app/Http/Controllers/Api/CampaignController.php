<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CampaignController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = Campaign::query()
                ->with(['project', 'source'])
                ->get();

        return CampaignResource::collection($data);
    }
}
