<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get("/", [UserController::class,'index']);
Route::get("/user", [UserController::class, 'user'])->middleware("auth:api");
Route::get("/getuser/{id}", [UserController::class, 'getUser'])->middleware("auth:api");
Route::get("/users", [UserController::class, 'users'])->middleware("auth:api");
Route::get("/chat/user/{id}", [UserController::class, 'getChat'])->middleware("auth:api");
Route::post("/chat/user/{id}", [UserController::class, 'send'])->middleware("auth:api");
