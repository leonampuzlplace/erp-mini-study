<?php

use Illuminate\Support\Facades\Route;

/**
 * Unit (Unidade de Medida)
 */
Route::group([
  'middleware' => ['api', 'jwt', 'cors', 'acl', 'X-Locale'],
  'namespace' => 'App\Http\Controllers\Unit',
], function () {
  Route::get("/unit",         "UnitController@index")->name("unit.index");
  Route::get("/unit/{id}",    "UnitController@show")->name("unit.show");
});