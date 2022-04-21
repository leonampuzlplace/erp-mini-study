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
  $uri = 'tenant';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@update")->name("${uri}.destroy");
});

// User (usuários)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\User\UserController';
  $uri = 'user';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@destroy")->name("${uri}.destroy");
});

// Role (Perfil de usuário)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\User\RoleController';
  $uri = 'role';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@destroy")->name("${uri}.destroy");
});

// City (Cidade)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\City\CityController';
  $uri = 'city';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
});

// State (UF)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\State\StateController';
  $uri = 'state';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
});

// Unit (Unidade de Medida)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Unit\UnitController';
  $uri = 'unit';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
});

// Person (Pessoas)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Person\PersonController';
  $uri = 'person';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@destroy")->name("${uri}.destroy");
});

// Category (Categoria)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Category\CategoryController';
  $uri = 'category';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@destroy")->name("${uri}.destroy");
});

// Brand (Marcas)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Brand\BrandController';
  $uri = 'brand';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@destroy")->name("${uri}.destroy");
});

// Stock (Produtos/Serviços)
Route::group([
  'middleware' => $defaultMiddleware
], function () {
  $ctrl = 'App\Http\Controllers\Stock\StockController';
  $uri = 'stock';
  Route::get("/${uri}",         "${ctrl}@index")->name("${uri}.index");
  Route::get("/${uri}/{id}",    "${ctrl}@show")->name("${uri}.show");
  Route::post("/${uri}",        "${ctrl}@store")->name("${uri}.store");
  Route::put("/${uri}/{id}",    "${ctrl}@update")->name("${uri}.update");
  Route::delete("/${uri}/{id}", "${ctrl}@destroy")->name("${uri}.destroy");
});