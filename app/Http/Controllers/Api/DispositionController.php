<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DispositionResource;
use App\Models\Disposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DispositionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cache_key = str(self::class)->replace('\\', ' ')->snake()->toString();

        $data = Cache::rememberForever($cache_key, function () {
            return Disposition::query()
                ->get();
        });

        return DispositionResource::collection($data);
    }
}
