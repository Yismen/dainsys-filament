<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'year' => ['nullable', 'integer', 'digits:4'],
        ]);

        $data = Holiday::query()
            ->when(
                $request->filled('year'),
                fn (Builder $query): Builder => $query->whereYear('date', (int) $request->query('year')),
            )
            ->get();

        return HolidayResource::collection($data);
    }
}
