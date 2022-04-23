<?php

use Illuminate\Support\Facades\Route;

/**
 * Category (Categoria)
 */
Route::group([
  'middleware' => ['api', 'jwt', 'cors', 'acl', 'X-Locale'],
  'namespace' => 'App\Http\Controllers\Category',
], function () {
  Route::get("/category",         "CategoryController@index")->name("category.index");
  Route::post("/category",        "CategoryController@store")->name("category.store");
  Route::get("/category/{id}",    "CategoryController@show")->name("category.show");
  Route::put("/category/{id}",    "CategoryController@update")->name("category.update");
  Route::delete("/category/{id}", "CategoryController@destroy")->name("category.destroy");
});