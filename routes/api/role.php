<?php

use Illuminate\Support\Facades\Route;

/**
 * Role (Perfil de usuários)
 */
Route::group([
  'middleware' => ['api', 'jwt', 'cors', 'acl', 'X-Locale'],
  'namespace' => 'App\Http\Controllers\User',
], function () {
  Route::get("/role/permission-template", "RoleController@permissionTemplate")->name("role.permissionTemplate");
  Route::get("/role",                     "RoleController@index")->name("role.index");
  Route::post("/role",                    "RoleController@store")->name("role.store");
  Route::get("/role/{id}",                "RoleController@show")->name("role.show");
  Route::put("/role/{id}",                "RoleController@update")->name("role.update");
  Route::delete("/role/{id}",             "RoleController@destroy")->name("role.destroy");
});