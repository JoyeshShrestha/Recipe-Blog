<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);



Route::post('/register', [UserController::class, 'register']);
Route::get('/users/{id}', [UserController::class, 'getUser']);
Route::get('/users', [UserController::class, 'getAllUser']);


Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});