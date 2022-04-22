<?php

use Illuminate\Support\Facades\Route;

/**
 * Stock (Produtos e Serviços)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => 'App\Http\Controllers\Stock',
], function () {
  Route::get("/stock",         "StockController@index")->name("stock.index");
  Route::post("/stock",        "StockController@store")->name("stock.store");
  Route::get("/stock/{id}",    "StockController@show")->name("stock.show");
  Route::put("/stock/{id}",    "StockController@update")->name("stock.update");
  Route::delete("/stock/{id}", "StockController@destroy")->name("stock.destroy");
});