<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Invoice API Routes
Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceApiController::class, 'index']);
    Route::get('/statistics', [InvoiceApiController::class, 'statistics']);
    Route::get('/{id}', [InvoiceApiController::class, 'show']);
});

// Finance Dashboard API Routes
Route::prefix('finance')->group(function () {
    Route::get('/invoices', [InvoiceApiController::class, 'getInvoices']);
    Route::get('/quotations', [InvoiceApiController::class, 'getQuotations']);
    Route::get('/recent-transactions', [InvoiceApiController::class, 'getRecentTransactions']);
});
