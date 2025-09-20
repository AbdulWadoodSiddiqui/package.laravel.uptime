<?php

use Illuminate\Support\Facades\Route;
use Uptime\Monitoring\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Monitoring API Routes
|--------------------------------------------------------------------------
|
| These routes are used by remote projects to send monitoring data
| to the central monitoring platform.
|
*/

Route::prefix('api')->middleware('api-key-auth')->group(function () {
    Route::post('/log-api-response', [ApiController::class, 'logApiResponse'])->name('monitoring.api.log-api-response');
    Route::post('/log-route-access', [ApiController::class, 'logRouteAccess'])->name('monitoring.api.log-route-access');
    Route::get('/stats', [ApiController::class, 'getProjectStats'])->name('monitoring.api.stats');
    Route::get('/health', [ApiController::class, 'healthCheck'])->name('monitoring.api.health');
});
