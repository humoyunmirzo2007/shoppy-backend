<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Modules\Information\Controllers\UserController;
use App\Modules\Information\Controllers\CategoryController;
use App\Modules\Information\Controllers\SupplierController;
use App\Modules\Information\Controllers\CostTypeController;
use App\Modules\Information\Controllers\ProductController;
use App\Modules\Information\Controllers\OtherSourceController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'getMe'])->middleware('auth:sanctum');
    Route::get('/generate-captcha', [AuthController::class, 'getCaptcha']);
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/get-all', [UserController::class, 'getAll'])->middleware(['reject_request']);
    Route::post('/create', [UserController::class, 'store']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::put('/update-password', [UserController::class, 'updatePassword']);
    Route::put('/invert-active/{id}', [UserController::class, 'invertActive']);
});

Route::group(['prefix' => 'categories', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/all-names', [CategoryController::class, 'getAll'])->middleware(['reject_request']);
    Route::get('/all-active', [CategoryController::class, 'getAllActive'])->middleware('reject_request');
    Route::post('/create', [CategoryController::class, 'store']);
    Route::put('/update/{id}', [CategoryController::class, 'update']);
    Route::put('/invert-active/{id}', [CategoryController::class, 'invertActive']);
});

Route::group(['prefix' => 'suppliers', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [SupplierController::class, 'getAll']);
    Route::get('/all-active', [SupplierController::class, 'getAllActive'])->middleware('reject_request');
    Route::post('/create', [SupplierController::class, 'store']);
    Route::put('/update/{id}', [SupplierController::class, 'update']);
    Route::put('/invert-active/{id}', [SupplierController::class, 'invertActive']);
});

Route::group(['prefix' => 'cost-types', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CostTypeController::class, 'getAll']);
    Route::get('/all-active', [CostTypeController::class, 'getAllActive'])->middleware('reject_request');
    Route::post('/create', [CostTypeController::class, 'store']);
    Route::put('/update/{id}', [CostTypeController::class, 'update']);
    Route::put('/invert-active/{id}', [CostTypeController::class, 'invertActive']);
});

Route::group(['prefix' => 'products', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [ProductController::class, 'getAll']);
    Route::post('/create', [ProductController::class, 'store']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
    Route::put('/invert-active/{id}', [ProductController::class, 'invertActive']);
    Route::get('/download-template', [ProductController::class, 'downloadTemplate']);
    Route::post('/import', [ProductController::class, 'import']);
});

Route::group(['prefix' => 'other-sources', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [OtherSourceController::class, 'getAll']);
    Route::get('/by-type-active', [OtherSourceController::class, 'getByTypeAllActive']);
    Route::post('/create', [OtherSourceController::class, 'store']);
    Route::put('/update/{id}', [OtherSourceController::class, 'update']);
    Route::put('/invert-active/{id}', [OtherSourceController::class, 'invertActive']);
});
