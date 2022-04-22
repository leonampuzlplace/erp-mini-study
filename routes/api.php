<?php

use Illuminate\Support\Facades\Route;

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
 * Auxilio para configuração das rotas
 */
class ApiRoute{
  public static function DefaultMiddleWare(): array
  {
    return ['api', 'jwt', 'cors', 'locale', 'acl'];
  }
  public static function DefaultPathController(): string
  {
    return 'App\Http\Controllers';
  }
}