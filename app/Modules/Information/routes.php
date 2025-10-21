<?php

use App\Http\Controllers\AuthController;
use App\Modules\Information\Controllers\AttributeController;
use App\Modules\Information\Controllers\AttributeValueController;
use App\Modules\Information\Controllers\BrandController;
use App\Modules\Information\Controllers\CategoryController;
use App\Modules\Information\Controllers\ClientController;
use App\Modules\Information\Controllers\CostTypeController;
use App\Modules\Information\Controllers\OtherSourceController;
use App\Modules\Information\Controllers\PaymentTypeController;
use App\Modules\Information\Controllers\ProductController;
use App\Modules\Information\Controllers\ProductVariantController;
use App\Modules\Information\Controllers\SupplierController;
use App\Modules\Information\Controllers\UserController;
use App\Modules\Information\Controllers\VariantAttributeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'getMe'])->middleware('auth:sanctum');
    Route::get('/generate-captcha', [AuthController::class, 'getCaptcha']);
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/get-all', [UserController::class, 'getAll'])->middleware(['reject_request']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/create', [UserController::class, 'store']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::put('/update-password', [UserController::class, 'updatePassword']);
    Route::put('/invert-active/{id}', [UserController::class, 'invertActive']);
});

Route::group(['prefix' => 'suppliers', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [SupplierController::class, 'getAll']);
    Route::get('/all-active', [SupplierController::class, 'getAllActive'])->middleware('reject_request');
    Route::get('/all-with-debt', [SupplierController::class, 'getAllWithDebt']);
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::post('/create', [SupplierController::class, 'store']);
    Route::put('/update/{id}', [SupplierController::class, 'update']);
    Route::put('/invert-active/{id}', [SupplierController::class, 'invertActive']);
});

Route::group(['prefix' => 'clients', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [ClientController::class, 'getAll']);
    Route::get('/all-active', [ClientController::class, 'getAllActive'])->middleware('reject_request');
    Route::get('/all-with-debt', [ClientController::class, 'getAllWithDebt']);
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::post('/create', [ClientController::class, 'store']);
    Route::put('/update/{id}', [ClientController::class, 'update']);
    Route::put('/invert-active/{id}', [ClientController::class, 'invertActive']);
});

Route::group(['prefix' => 'cost-types', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CostTypeController::class, 'getAll']);
    Route::get('/all-active', [CostTypeController::class, 'getAllActive'])->middleware('reject_request');
    Route::get('/{id}', [CostTypeController::class, 'show']);
    Route::post('/create', [CostTypeController::class, 'store']);
    Route::put('/update/{id}', [CostTypeController::class, 'update']);
    Route::put('/invert-active/{id}', [CostTypeController::class, 'invertActive']);
});

Route::group(['prefix' => 'products', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [ProductController::class, 'getAll']);
    Route::get('/residues', [ProductController::class, 'getForResidues']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/create', [ProductController::class, 'store']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
    Route::put('/invert-active/{id}', [ProductController::class, 'invertActive']);
    Route::get('/download-template', [ProductController::class, 'downloadTemplate']);
    Route::post('/import', [ProductController::class, 'import']);
    Route::get('/download-update-price-template', [ProductController::class, 'downloadUpdatePriceTemplate']);
    Route::post('/update-prices-from-template', [ProductController::class, 'updatePricesFromTemplate']);
});

Route::group(['prefix' => 'other-sources', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [OtherSourceController::class, 'getAll']);
    Route::get('/by-type-active', [OtherSourceController::class, 'getByTypeAllActive']);
    Route::get('/{id}', [OtherSourceController::class, 'show']);
    Route::post('/create', [OtherSourceController::class, 'store']);
    Route::put('/update/{id}', [OtherSourceController::class, 'update']);
    Route::put('/invert-active/{id}', [OtherSourceController::class, 'invertActive']);
});

Route::group(['prefix' => 'payment-types', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PaymentTypeController::class, 'index']);
    Route::get('/all-active', [PaymentTypeController::class, 'getAllActive'])->middleware('reject_request');
    Route::get('/{id}', [PaymentTypeController::class, 'show']);
    Route::post('/create', [PaymentTypeController::class, 'store']);
    Route::put('/update/{id}', [PaymentTypeController::class, 'update']);
    Route::put('/invert-active/{id}', [PaymentTypeController::class, 'invertActive']);
});

Route::group(['prefix' => 'categories', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/active', [CategoryController::class, 'active']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::get('/{id}/with-parents', [CategoryController::class, 'showWithParents']);
    Route::post('/create', [CategoryController::class, 'store']);
    Route::put('/update/{category}', [CategoryController::class, 'update']);
    Route::put('/toggle-active/{id}', [CategoryController::class, 'toggleActive']);
});

// Brands CRUD routes
Route::group(['prefix' => 'brands', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [BrandController::class, 'index']);
    Route::get('/active', [BrandController::class, 'getAllActive']);
    Route::get('/{id}', [BrandController::class, 'show']);
    Route::post('/create', [BrandController::class, 'store']);
    Route::put('/update/{id}', [BrandController::class, 'update']);
    Route::put('/toggle-active/{id}', [BrandController::class, 'toggleActive']);
});

// Attributes CRUD routes (Admin only)
Route::group(['prefix' => 'attributes', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [AttributeController::class, 'index']);
    Route::get('/active', [AttributeController::class, 'getAllActive']);
    Route::get('/{id}', [AttributeController::class, 'show']);
    Route::post('/create', [AttributeController::class, 'store']);
    Route::put('/update/{id}', [AttributeController::class, 'update']);
    Route::put('/toggle-active/{id}', [AttributeController::class, 'toggleActive']);
});

// Attribute Values CRUD routes (Admin only)
Route::group(['prefix' => 'attribute-values', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [AttributeValueController::class, 'index']);
    Route::get('/by-attribute/{attributeId}', [AttributeValueController::class, 'getByAttribute']);
    Route::get('/active', [AttributeValueController::class, 'getAllActive']);
    Route::get('/{id}', [AttributeValueController::class, 'show']);
    Route::post('/create', [AttributeValueController::class, 'store']);
    Route::put('/update/{id}', [AttributeValueController::class, 'update']);
    Route::put('/toggle-active/{id}', [AttributeValueController::class, 'toggleActive']);
});

// Product Variants CRUD routes
Route::group(['prefix' => 'product-variants', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [ProductVariantController::class, 'index']);
    Route::get('/by-product/{productId}', [ProductVariantController::class, 'getByProduct']);
    Route::get('/active', [ProductVariantController::class, 'getAllActive']);
    Route::get('/{id}', [ProductVariantController::class, 'show']);
    Route::post('/create', [ProductVariantController::class, 'store']);
    Route::put('/update/{id}', [ProductVariantController::class, 'update']);
    Route::put('/toggle-active/{id}', [ProductVariantController::class, 'toggleActive']);
});

// Variant Attributes CRUD routes
Route::group(['prefix' => 'variant-attributes', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [VariantAttributeController::class, 'index']);
    Route::get('/by-variant/{productVariantId}', [VariantAttributeController::class, 'getByVariant']);
    Route::get('/{id}', [VariantAttributeController::class, 'show']);
    Route::post('/create', [VariantAttributeController::class, 'store']);
    Route::put('/update/{id}', [VariantAttributeController::class, 'update']);
    Route::delete('/delete-by-variant/{productVariantId}', [VariantAttributeController::class, 'deleteByVariant']);
});
