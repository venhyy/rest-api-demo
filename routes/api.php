<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
});



Route::controller(PostController::class)->group(function () {
    Route::get("/posts/all", "all");
    Route::get("/posts/{post}", "show");
});


Route::middleware(["auth:sanctum"])->group(function () {
    Route::controller(PostController::class)->group(function () {
        Route::get("/posts", "index");
        Route::post("/posts", "store");
        Route::put("/posts/{post}", "update");
        Route::delete("/posts/{post}", "destroy");
    });
});
