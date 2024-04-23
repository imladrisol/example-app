<?php

use App\Http\Controllers\Api\V1\IncidentController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->middleware([EnsureTokenIsValid::class])->group(function () {
    Route::post('/incidents', [IncidentController::class, 'store']);
    Route::get('/incidents', [IncidentController::class, 'index']);
    Route::get('/incidents/{incident}', [IncidentController::class, 'show']);
    Route::put('/incidents/{incident}', [IncidentController::class, 'update']);
    Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy']);
});
