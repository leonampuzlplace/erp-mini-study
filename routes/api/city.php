<?php

use Illuminate\Support\Facades\Route;

/**
 * City (Cidade)
 */
Route::group([
  'middleware' => ['api', 'jwt', 'cors', 'acl', 'X-Locale'],
  'namespace' => 'App\Http\Controllers\City',
], function () {
  Route::get("/city",      "CityController@index")->name("city.index");
  Route::get("/city/{id}", "CityController@show")->name("city.show");
});
