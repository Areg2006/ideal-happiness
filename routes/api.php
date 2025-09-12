<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthenticationController::class, 'store']);
    Route::post('/logout', [AuthenticationController::class, 'destroy']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
    });
    Route::get('/products/archived', [ProductController::class, 'archived']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/products/{id}/archive', [ProductController::class, 'archive']);
    Route::post('/products/{id}/unarchive', [ProductController::class, 'unarchive']);
    Route::post('/products/{id}/buy', [ProductController::class, 'buy']);
});


Route::post('/categories-store', [CategoryController::class, 'store']);
Route::get('/categories-show/{id}', [CategoryController::class, 'show']);
Route::get('/categories-index', [CategoryController::class, 'index']);
Route::patch('/categories-update/{id}', [CategoryController::class, 'update']);
Route::delete('/categories-delete/{id}', [CategoryController::class, 'destroy']);



Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/create', [UserController::class, 'store']);
});

Route::get('/users/{id}/show', [UserController::class, 'show']);
Route::put('/users/{id}/update', [UserController::class, 'update']);
Route::patch('/users/{id}/update', [UserController::class, 'update']);
Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);















/*Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthenticationController::class, 'store']);
    Route::post('/logout', [AuthenticationController::class, 'destroy']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
    });
});


Route::post('/products-store', [ProductController::class, 'store']);
Route::get('/products-show/{id}', [ProductController::class, 'show']);
Route::get('/products-index', [ProductController::class, 'index']);
Route::middleware('auth:api')->post('/products/{id}/buy', [ProductController::class, 'buy']);
Route::middleware('check:role')->get('/products-index/{search?}/{id?}', [ProductController::class, 'index']);
Route::patch('/products-update/{id}', [ProductController::class, 'update']);
Route::delete('/products-destroy/{id}', [ProductController::class, 'destroy']);
Route::patch('/products-archive-attach/{id}', [ProductController::class, 'attach']);
Route::patch('/products-archive-detach/{id}', [ProductController::class, 'detach']);
Route::get('/products-archived', [ProductController::class, 'showArchiveProduct']);*/
