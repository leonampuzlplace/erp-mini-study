<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Tenant\TenantController;
use App\Http\Controllers\Person\PersonController;
use App\Http\Controllers\State\StateController;
use App\Http\Controllers\Person\PersonTypeController;
use App\Http\Controllers\User\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('auth/login', [AuthController::class, 'login']);

Route::group([
  'middleware' => ['apiJwt']
], function(){
  Route::apiResource('/user', UserController::class);
});

Route::apiResource('/tenant', TenantController::class);
Route::apiResource('/city', CityController::class);
Route::apiResource('/state', StateController::class);
Route::apiResource('/person-type', PersonTypeController::class);
Route::apiResource('/person', PersonController::class);