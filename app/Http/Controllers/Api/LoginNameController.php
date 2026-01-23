<?php

namespace App\Http\Controllers\Api;

use App\Models\LoginName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoginNameResource;
use App\Http\Resources\LoginNameCollection;
use App\Http\Resources\LoginNamesCollectionResource;
use App\Http\Resources\LoginName as ResourcesLoginName;
use Illuminate\Support\Facades\Auth;
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
                ->with(['employee'])
                ->get();
        });

        return LoginNameResource::collection($data);
    }
}
