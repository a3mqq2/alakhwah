<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // count = total price 
    public function index() {
        $total_price = Contract::whereIn('contract_status', ["ساري", "مكتمل"])->sum('installments');
        $total_due = Contract::whereIn('contract_status', ["ساري", "مكتمل"])->sum('due');
        $total_paid = Contract::whereIn('contract_status', ["ساري", "مكتمل"])->sum('paid');
        $payments_count = Payment::count();
        $total_profit = $total_paid - ($payments_count * 10);
        $bank_tax = $payments_count * 10;
        return view("home", compact('total_price','total_paid','payments_count','total_profit','bank_tax','total_due'));
    }
}

