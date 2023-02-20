<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\ProductController;
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

Route::post("/auth/login", [AuthController::class, 'Auth']);

Route::get("/testing", [AuthController::class, 'testing'])->middleware('auth:api');

Route::get("/refresh", [AuthController::class, 'refresh'])->middleware('auth:api');

Route::get("/product", [ProductController::class, 'index'])->name("product")->middleware('auth:api');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
