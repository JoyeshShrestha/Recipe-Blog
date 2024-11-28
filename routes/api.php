<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;

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
Route::put('/users/update/{id}', [UserController::class, 'updateUser']);
Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser']);
Route::patch('/users/changepassword/{id}', [UserController::class, 'changePassword']);



Route::post('/recipe/add', [RecipeController::class, 'addRecipe']);
Route::get('/recipe/{id}', [RecipeController::class, 'getRecipe']);
Route::get('/latestrecipe', [RecipeController::class, 'getLatestRecipe']);

Route::get('/recipe', [RecipeController::class, 'getAllRecipe']);
Route::put('/recipe/update/{id}', [RecipeController::class, 'updateRecipe']);

Route::delete('/recipe/delete/{id}', [RecipeController::class, 'deleteRecipe']);



// Route::post('/recipe/add', [RecipeController::class, 'addRecipe'])->middleware('api');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['web'])->group(function () {
    Route::post('/api/logout', [AuthController::class, 'logout']);
});
