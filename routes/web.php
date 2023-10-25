<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ProkerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', [DashboardController::class, "index"])->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::middleware('auth')->prefix("admin")->name("admin.")->group(function () {
    Route::view('about', 'about')->name('about');

    Route::group(["middleware" => "role:admin|bph"], function()
    {
        // Route Anggota
        Route::resource("anggota", MemberController::class)->except("create", "store", "edit");
        Route::delete("anggota", [MemberController::class, "destroyAll"])->name("anggota.destroy-all");
    });

    // Route Artikel
    Route::resource("artikel", ArticleController::class)->except("show");
    Route::get("artikel/{artikel}/komentar", [ArticleController::class, "comments"])->name("artikel.komentar");
    Route::delete("artikel/{komentar}", [ArticleController::class, "destroy_comment"])->name("artikel.komentar.destroy");

    // Route Proker
    Route::resource("proker", ProkerController::class)->except("show");
    Route::get("proker/{proker}/komentar", [ProkerController::class, "comments"])->name("proker.komentar");
    Route::delete("proker/{komentar}", [ProkerController::class, "destroy_comment"])->name("proker.komentar.destroy");
    Route::get("proker/{proker}/galeri", [ProkerController::class, "galleries"])->name("proker.galeri");
    Route::delete("proker/{galeri}/galeri", [ProkerController::class, "destroy_gallery"])->name("proker.galeri.destroy");
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
