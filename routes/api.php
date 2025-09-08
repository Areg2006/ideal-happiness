<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::post('/products-store', [ProductController::class, 'store']);
Route::get('/products-show/{id}', [ProductController::class, 'show']);
Route::get('/products-index', [ProductController::class, 'index']);


Route::middleware('check:role')->get('/products-index/{search?}/{id?}', [ProductController::class, 'index']);
Route::patch('/products-update/{id}', [ProductController::class, 'update']);
Route::delete('/products-destroy/{id}', [ProductController::class, 'destroy']);


Route::patch('/products-archive-attach/{id}', [ProductController::class, 'attach']);
Route::patch('/products-archive-detach/{id}', [ProductController::class, 'detach']);
Route::get('/products-archived', [ProductController::class, 'showArchiveProduct']);


Route::post('/categories-store', [CategoryController::class, 'store']);
Route::get('/categories-show/{id}', [CategoryController::class, 'show']);
Route::get('/categories-index', [CategoryController::class, 'index']);
Route::patch('/categories-update/{id}', [CategoryController::class, 'update']);
Route::delete('/categories-delete/{id}', [CategoryController::class, 'destroy']);


Route::Post('/users-store', [UserController::class, 'store']);
Route::get('/users-show/{id}', [UserController::class, 'show']);
Route::get('/users-index/{id}', [UserController::class, 'index']);
Route::patch('/users-update/{id}', [UserController::class, 'update']);
Route::delete('/users-destroy/{id}', [UserController::class, 'destroy']);


Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthenticationController::class, 'store']);
    Route::post('/logout', [AuthenticationController::class, 'destroy']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
    });

});
