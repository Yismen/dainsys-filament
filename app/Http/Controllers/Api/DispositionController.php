<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DispositionResource;
use App\Models\Disposition;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = Disposition::query()
            ->get();

        return DispositionResource::collection($data);
    }
}
