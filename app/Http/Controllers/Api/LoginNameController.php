<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginNameResource;
use App\Models\LoginName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginNameController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cache_key = str(self::class)->replace('\\', ' ')->snake()->toString();

        $data = Cache::rememberForever($cache_key, function () {
            return LoginName::query()
                ->withWhereHas('employees', function ($query) {
                    $query->activesOrRecentlyTerminated();
                })
                ->get();
        });

        return LoginNameResource::collection($data);
    }
}
