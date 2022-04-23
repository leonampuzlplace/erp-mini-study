<?php

use Illuminate\Support\Facades\Route;

// Auth (Autenticação)
Route::group([
  'namespace' => 'App\Http\Controllers',
  'middleware' => ['api', 'X-Locale'],
  'prefix' => 'auth',
], function () {
  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');
});