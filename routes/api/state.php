<?php

use Illuminate\Support\Facades\Route;

/**
 * State (UF)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => ApiRoute::DefaultPathController().'\State',  
], function () {
  Route::get("/state",      "StateController@index")->name("state.index");
  Route::get("/state/{id}", "StateController@show")->name("state.show");
});

