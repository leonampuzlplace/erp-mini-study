<?php

use Illuminate\Support\Facades\Route;

/**
 * Brand (Marca)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => ApiRoute::DefaultPathController().'\Brand',
], function () {
  Route::get("/brand",         "BrandController@index")->name("brand.index");
  Route::post("/brand",        "BrandController@store")->name("brand.store");
  Route::get("/brand/{id}",    "BrandController@show")->name("brand.show");
  Route::put("/brand/{id}",    "BrandController@update")->name("brand.update");
  Route::delete("/brand/{id}", "BrandController@destroy")->name("brand.destroy");
});