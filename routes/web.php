<?php

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ConstractController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\WarehouseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');
Route::view('/login', 'login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/users/{user}/active', [UsersController::class, 'active'])->name('users.active');
    Route::resource('/users', UsersController::class);
    Route::get('/contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print');
    Route::view('/contracts/import', 'contracts.import')->name('contracts.import_view');
    Route::resource('/contracts', ContractController::class);
    Route::resource('/banks', BankController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/payments', PaymentController::class);
    Route::get('/export-pdf', [StatementController::class, 'generatePdf']);
    Route::post('/contracts/import_excel', [StatementController::class, 'store_excel'])->name('contracts.store_excel');
    Route::get('/reports/rating', [ReportController::class, 'rating'])->name('reports.rating');
    Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/payments', [ReportController::class, 'getUnpaidContracts'])->name('reports.payments');
    Route::get('/statements/{statement}/print', [StatementController::class, 'print'])->name('statements.print');
    Route::resource('/statements', StatementController::class);
});

Route::get('/update-contracts', function() {
    $contracts = Contract::where('due', '<=', 0)->get();
    foreach($contracts as $contract) {
        $contract->contract_status = "مكتمل";
        $contract->save();
    }
});


Route::get('/create-payments/{number}', function($number) {
    try {
        DB::beginTransaction();
        $contract = Contract::find($number);
        $contract->paid = 0;
        $contract->due = $contract->installments;
        $contract->save();
        $monthsArray = $contract->months_array;
        foreach($monthsArray as $month) {
        
            $contract->decrement('due', $contract->monthly_deduction);
            $contract->increment('paid', $contract->monthly_deduction);

            $payment = new Payment();
            $payment->contract_id = $contract->id;
            $payment->customer_id = $contract->customer_id;
            $payment->month = Carbon::parse($month);
            $payment->paid = $contract->paid;
            $payment->due = $contract->due;
            $payment->amount = $contract->monthly_deduction;
            $payment->save();
        }

        if($contract->paid >= $contract->installments) {
            $contract->update(['status' => "مكتمل"]);
        }

        DB::commit();
    } catch(Exception $e) {

    }
});