<?php
// backend/routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StationsController;
use App\Http\Controllers\Api\DistancesController;
use App\Http\Controllers\Api\RoutesController;
use App\Http\Controllers\Api\AnalyticsController;

// authentication (OpenAPI uses bearerAuth)
Route::post('v1/auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('v1/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::prefix('v1')->middleware('auth:api')->group(function () {
	// protected auth helpers
	Route::post('auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
	Route::get('auth/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);

	// Stations (protected)
	Route::get('stations', [StationsController::class, 'index']);
	Route::get('stations/p/{short}', [StationsController::class, 'findByShort']);
	Route::get('stations/{id}', [StationsController::class, 'show']);

	// Distances
	Route::get('distances', [DistancesController::class, 'index']);
	Route::get('distances/{id}', [DistancesController::class, 'show']);
	Route::get('distance-between', [DistancesController::class, 'between']);

	// Routes (routing calculation)
	Route::post('routes', [RoutesController::class, 'store']);

	// Analytics
	Route::get('stats/distances', [AnalyticsController::class, 'distances']);
});
