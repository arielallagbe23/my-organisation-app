<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CitationController;
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

Route::post('register', [UserController::class,'register']);

Route::post('login', [UserController::class,'login'])->name('login');

Route::group(['middleware'=>'auth:sanctum'],function () {

    Route::get('user', [UserController::class,'user']);

    Route::post('logout', [UserController::class,'logout']);

    //Route::get('all-menu-2', [MenuController::class,'allMenu2']);
    
});

Route::put('user/{id}', [UserController::class, 'updateUser'])->name('user.update');

Route::delete('user/{id}', [UserController::class, 'destroyUser'])->name('user.destroy');



Route::post('create-menu', [MenuController::class,'createMenu']);

Route::get('all-menu', [MenuController::class,'allMenu']);

Route::get('menu/{id}', [MenuController::class,'menu']);

Route::put('menu/{id}', [MenuController::class, 'updateMenu'])->name('menu.update');

Route::delete('menu/{id}', [MenuController::class, 'deleteMenu'])->name('menu.delete');



Route::post('create-type', [typeController::class,'createType']);

Route::get('all-type', [typeController::class,'allType']);

Route::get('type/{id}', [typeController::class,'type']);

Route::put('type/{id}', [typeController::class, 'updateType'])->name('type.update');

Route::delete('type/{id}', [typeController::class, 'deleteType'])->name('type.delete');



Route::post('create-citation', [CitationController::class,'createCitation']);

Route::get('all-citation', [CitationController::class,'allCitation']);

Route::get('citation/{id}', [CitationController::class,'citation']);

Route::put('citation/{id}', [CitationController::class, 'updateCitation'])->name('citation.update');

Route::delete('citation/{id}', [CitationController::class, 'deleteCitation'])->name('citation.delete');

