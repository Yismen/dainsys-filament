<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginNameResource;
use App\Models\LoginName;
use Illuminate\Http\Request;

class LoginNameController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = LoginName::query()
            ->withWhereHas('employee', function ($query): void {
                $query->activesOrRecentlyTerminatedLight();
            })
            ->get();

        return LoginNameResource::collection($data);
    }
}
