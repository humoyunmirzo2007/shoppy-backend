<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Modules\Information\Controllers\UserController;
use App\Modules\Information\Controllers\CategoryController;
use App\Modules\Information\Controllers\SupplierController;


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

