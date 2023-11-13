<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
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
    
});