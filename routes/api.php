<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\FlightController;
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
Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/airplanes', [AirplaneController::class, 'getAirplanes']);
    Route::get('/passengers', [PassengerController::class, 'index']);
    Route::get('/passengers/{id}', [PassengerController::class, 'show']);
    Route::get('/crews', [CrewController::class, 'index']);
    Route::get('/crews/{id}', [CrewController::class, 'show']);
    Route::get('/costs', [CostController::class, 'index']);
    Route::get('/costs/{id}', [CostController::class, 'show']);
    Route::get('/flights', [FlightController::class, 'index']);
    Route::get('/flights/{id}', [FlightController::class, 'show']);

    Route::get('/flights/{id}/details', [FlightController::class, 'showDetailedFlight']);
});

Route::middleware(['auth:sanctum', 'role:operator2'])->group(function() {
    Route::resource('airplanes', AirplaneController::class)->except(['index', 'show']);
    Route::resource('passengers', PassengerController::class)->except(['index', 'show']);
    Route::resource('crews', CrewController::class)->except(['index', 'show']);
    Route::resource('costs', CostController::class)->except(['index', 'show']);
    Route::resource('flights', FlightController::class)->except(['index', 'show']);
// Show passengers for a flight
Route::get('/flights/{id}/passengers', [FlightController::class, 'showPassengers']);

// Add passenger to a flight
Route::post('/flights/{flight_id}/passengers', [FlightController::class, 'addPassengerToFlight']);

// Remove passenger from a flight
Route::delete('/flights/{flight_id}/passengers', [FlightController::class, 'removePassengerFromFlight']);


    // Route::post('/flights/{flight_id}/addPassenger', [FlightController::class, 'addPassengerToFlight']);
    // Route::post('/flights/checkAirplaneAvailability', [FlightController::class, 'checkAirplaneAvailability']);
       

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);





