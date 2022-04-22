<?php

use Illuminate\Support\Facades\Route;

/**
 * Unit (Unidade de Medida)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => ApiRoute::DefaultPathController().'\Unit',  
], function () {
  Route::get("/unit",         "UnitController@index")->name("unit.index");
  Route::get("/unit/{id}",    "UnitController@show")->name("unit.show");
});