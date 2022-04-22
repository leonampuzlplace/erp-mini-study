<?php

use Illuminate\Support\Facades\Route;

/**
 * Tenant (Inquilinos)
 */
Route::group([
  'middleware' => ApiRoute::DefaultMiddleWare(),
  'namespace' => ApiRoute::DefaultPathController().'\Tenant',  
], function () {
  Route::get("/tenant",         "TenantController@index")->name("tenant.index");
  Route::get("/tenant/{id}",    "TenantController@show")->name("tenant.show");
  Route::post("/tenant",        "TenantController@store")->name("tenant.store");
  Route::put("/tenant/{id}",    "TenantController@update")->name("tenant.update");
  Route::delete("/tenant/{id}", "TenantController@update")->name("tenant.destroy");
});