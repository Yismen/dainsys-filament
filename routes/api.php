<?php

use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DispositionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\LoginNameController;
use App\Http\Controllers\Api\PayrollHourController;
use App\Http\Controllers\Api\ProductionController;
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
        Route::get('campaigns', CampaignController::class);
        Route::get('login_names', LoginNameController::class);
        Route::get('productions', ProductionController::class);
        Route::get('payroll_hours', PayrollHourController::class);
        Route::get('employees', EmployeeController::class);
        Route::get('dispositions', DispositionController::class);
    });
