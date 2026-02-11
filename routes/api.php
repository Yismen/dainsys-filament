<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'ability:use-dainsys'])
    ->group(function (): void {
        Route::get('campaigns', \App\Http\Controllers\Api\CampaignController::class);
        Route::get('login_names', \App\Http\Controllers\Api\LoginNameController::class);
        Route::get('productions', \App\Http\Controllers\Api\ProductionController::class);
        Route::get('employees', \App\Http\Controllers\Api\EmployeeController::class);
    });
