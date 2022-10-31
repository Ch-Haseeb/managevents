<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
 use App\Http\Controllers\EventController;
 use App\Http\Controllers\GoogleController;
 use App\Http\Controllers\SyncController;

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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::resource('/calandar',GoogleController::class);



Route::middleware('auth:sanctum')->group( function () {
    Route::post('event', [EventController::class, 'storeevent']);  
    Route::post('syncevent',[SyncController::class,'syncevent']);
    Route::get('getallevent',[SyncController::class,'getallevent']);
 

});




