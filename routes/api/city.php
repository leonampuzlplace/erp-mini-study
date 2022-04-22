<?php

use Illuminate\Support\Facades\Route;

/**
 * City (Cidade)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => ApiRoute::DefaultPathController().'\City',  
], function () {
  Route::get("/city",      "CityController@index")->name("city.index");
  Route::get("/city/{id}", "CityController@show")->name("city.show");
});
