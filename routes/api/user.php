<?php

use Illuminate\Support\Facades\Route;

/**
 * User (Usuários)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => ApiRoute::DefaultPathController().'\User',  
], function () {
  Route::get("user",         "UserController@index")->name("user.index");
  Route::get("user/{id}",    "UserController@show")->name("user.show");
  Route::post("user",        "UserController@store")->name("user.store");
  Route::put("user/{id}",    "UserController@update")->name("user.update");
  Route::delete("user/{id}", "UserController@destroy")->name("user.destroy");
});