<?php

use App\Modules\Warehouse\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Modules\Warehouse\Controllers\SupplierCalculationController;

Route::group(['prefix' => 'invoices', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/get-supplier-inputs', [InvoiceController::class, 'getInputs']);
    Route::get('/get-supplier-outputs', [InvoiceController::class, 'getOutputs']);
    Route::get('/get-by-id/{id}', [InvoiceController::class, 'getByIdWithProducts']);
    Route::post('/create', [InvoiceController::class, 'store']);
    Route::put('/update/{id}', [InvoiceController::class, 'update']);
    Route::delete('/delete/{id}', [InvoiceController::class, 'delete']);
});
Route::group(['prefix' => 'supplier-calculations', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/get-by-supplier/{supplierId}', [SupplierCalculationController::class, 'getBySupplierId']);
});
