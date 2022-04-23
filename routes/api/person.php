<?php

use Illuminate\Support\Facades\Route;

/**
 * Person (Pessoas)
 */
Route::group([
  'middleware' => ['api', 'jwt', 'cors', 'acl', 'X-Locale'],
  'namespace' => 'App\Http\Controllers\Person',
], function () {
  Route::get("/person",         "PersonController@index")->name("person.index");
  Route::post("/person",        "PersonController@store")->name("person.store");
  Route::get("/person/{id}",    "PersonController@show")->name("person.show");
  Route::put("/person/{id}",    "PersonController@update")->name("person.update");
  Route::delete("/person/{id}", "PersonController@destroy")->name("person.destroy");
});