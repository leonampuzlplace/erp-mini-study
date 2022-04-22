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

/**
 * Configuração de middleware padrão para as rotas
 */
class ApiRoute{public static function DefaultMiddleWare(){
  return ['api', 'jwt', 'cors', 'locale', 'acl'];
}}