<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Tenant\TenantController;
use App\Http\Controllers\Person\PersonController;
use App\Http\Controllers\State\StateController;
use App\Http\Controllers\Unit\UnitController;
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

Route::group([
  'prefix' => 'auth',
], function () {
  Route::post('login', [AuthController::class, 'login']);
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);
  Route::post('me', [AuthController::class, 'me']);  
});

Route::group([
  'middleware' => ['api', 'apiJwt', 'cors', 'localization'],
], function(){
  Route::apiResource('/user', UserController::class);
  Route::apiResource('/tenant', TenantController::class);
  Route::apiResource('/city', CityController::class);
  Route::apiResource('/state', StateController::class);
  Route::apiResource('/person', PersonController::class);
  Route::apiResource('/unit', UnitController::class);
  Route::apiResource('/category', CategoryController::class);
  Route::apiResource('/brand', BrandController::class);
});

