<?php

use App\Modules\Warehouse\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'invoices', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/get-supplier-inputs', [InvoiceController::class, 'getSupplierInputs']);
    Route::get('/get-supplier-outputs', [InvoiceController::class, 'getSupplierOutputs']);
    Route::get('/get-by-id/{id}', [InvoiceController::class, 'getByIdWithProducts']);
    Route::post('/create', [InvoiceController::class, 'store']);
    Route::put('/update/{id}', [InvoiceController::class, 'update']);
    Route::delete('/delete/{id}', [InvoiceController::class, 'delete']);
});
