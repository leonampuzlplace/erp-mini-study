<?php

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
// Auth (Autenticação)
Route::group([
  'namespace' => 'App\Http\Controllers',
  'middleware' => ['api', 'locale'],
  'prefix' => 'auth',
], function () {
  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');
});


// Midleware Padrão para as rotas
$defaultMiddleware = ['api', 'jwt', 'cors', 'locale', 'acl'];

// Tenant (Inquilinos)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Tenant\TenantController';
  $actionName = 'tenant';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",        "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",    "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}", "${ctrl}@update")->name("${actionName}.destroy");
});

// User (usuários)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\User\UserController';
  $actionName = 'user';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",        "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",    "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}", "${ctrl}@destroy")->name("${actionName}.destroy");
});

// Role (Perfil de usuário)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\User\RoleController';
  $actionName = 'role';
  Route::get("/${actionName}/permission-template", "${ctrl}@permissionTemplate")->name("${actionName}.permissionTemplate");
  Route::get("/${actionName}",                     "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",                "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",                    "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",                "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}",             "${ctrl}@destroy")->name("${actionName}.destroy");
});

// City (Cidade)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\City\CityController';
  $actionName = 'city';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
});

// State (UF)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\State\StateController';
  $actionName = 'state';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
});

// Unit (Unidade de Medida)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Unit\UnitController';
  $actionName = 'unit';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
});

// Person (Pessoas)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Person\PersonController';
  $actionName = 'person';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",        "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",    "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}", "${ctrl}@destroy")->name("${actionName}.destroy");
});

// Category (Categoria)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Category\CategoryController';
  $actionName = 'category';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",        "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",    "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}", "${ctrl}@destroy")->name("${actionName}.destroy");
});

// Brand (Marcas)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Brand\BrandController';
  $actionName = 'brand';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",        "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",    "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}", "${ctrl}@destroy")->name("${actionName}.destroy");
});

// Stock (Produtos/Serviços)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Stock\StockController';
  $actionName = 'stock';
  Route::get("/${actionName}",         "${ctrl}@index")->name("${actionName}.index");
  Route::get("/${actionName}/{id}",    "${ctrl}@show")->name("${actionName}.show");
  Route::post("/${actionName}",        "${ctrl}@store")->name("${actionName}.store");
  Route::put("/${actionName}/{id}",    "${ctrl}@update")->name("${actionName}.update");
  Route::delete("/${actionName}/{id}", "${ctrl}@destroy")->name("${actionName}.destroy");
});