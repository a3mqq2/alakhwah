<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StatementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/state/store', [StatementController::class, 'store']);
Route::get('/statements/init', [ReportController::class, 'init']);
Route::get('/payments/init', [PaymentController::class, 'init']);
Route::post('/payments/store', [PaymentController::class, 'store']);
Route::get('/statements/show', [ReportController::class, 'show_api']);
Route::get('/customers/{bank}', [CustomerController::class, 'get_customers']);
Route::get('/contracts/{customer}', [CustomerController::class, 'get_contracts']);
Route::get('/state/init', [StatementController::class, 'init']);