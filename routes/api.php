<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AspirasiController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\ProkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::prefix("auth")->group(function()
{
    Route::post("register", [MemberController::class, "register"]);
    Route::post("login", [MemberController::class, "login"]);
    Route::post("logout", [MemberController::class, "logout"]);
    Route::post("refresh", [MemberController::class, "refresh"]);
    Route::get("me", [MemberController::class, "me"]);
});

// Article Routes
Route::prefix("artikel")->group(function()
{
    Route::get("/", [ArticleController::class, "index"]);
    Route::get("/{artikel}", [ArticleController::class, "show"]);
    Route::post("/{artikel}/komentar", [ArticleController::class, "createComments"]);
});

// Proker Routes
Route::prefix("proker")->group(function()
{
    Route::get("/", [ProkerController::class, "index"]);
    Route::get("/{proker}", [ProkerController::class, "show"]);
    Route::post("/{proker}/komentar", [ProkerController::class, "createComments"]);
});

// Aspirasi Routes
Route::prefix("aspirasi")->group(function()
{
    Route::post("/", [AspirasiController::class, "createAspirasi"]);
});
