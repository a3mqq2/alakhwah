<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index() {
        $total_price = Contract::whereIn('contract_status', ["ساري", "مكتمل"])->sum('installments');
        $total_due = Contract::whereIn('contract_status', ["ساري", "مكتمل"])->sum('due');
        $total_paid = Contract::whereIn('contract_status', ["ساري", "مكتمل"])->sum('paid');
        $payments_count = Payment::count();
        $total_profit = $total_paid - ($payments_count * 10);
        $bank_tax = $payments_count * 10;
        $customers = Customer::select('id','name')->get();

        return view("reports.index", compact('total_price','total_paid','payments_count','total_profit','bank_tax','total_due','customers'));
    }   


    public function show_api(Request $request) {
        $month = $request->month;
        $bank_id = $request->bank;
    
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();
    
        $contracts = Contract::with('customer', 'payments')
            ->where('bank_id', $bank_id)
            ->where('contract_status', "ساري")
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('start_month', '<=', $endOfMonth)
                    ->where('end_month', '>=', $startOfMonth);
            })
            ->withCount(['payments as payments_count'])
            ->orderBy(DB::raw('payments_count = 0'), 'desc') // Orders by contracts without payments first
            ->orderBy('start_month') // or 'end_month', or any other column you want to order by
            ->get();

    
        $contracts->each(function ($contract) use ($month) {
            $contract->is_payment = $contract->checkIfPayment($month);
        });
    
        return response()->json(['contracts' => $contracts]);
    }

    

    public function payments(Request $request) {
        $request->validate([
            "from_month" => "required",
            "to_month" => "required",
        ]);


        $payments = Payment::when(request('customer_id'), function($q) {
            $q->where('customer_id', request('customer_id'));
        })
        ->whereBetween('month',[Carbon::parse($request->from_month), Carbon::parse($request->to_month)])
        ->orderBy('month')->get();
        $customer = Customer::find(request('customer_id'));
        return view('reports.payments', compact('payments','customer'));
    }


    public function init() {
        $banks = Bank::all();
        return response()->json(['banks' => $banks]);
    }


    public function rating() {
        return view('reports.rating');
    }
    

    public function getUnpaidContracts(Request $request)
    {
        $startDate = Carbon::parse($request->from_month);
        $endDate = Carbon::parse($request->to_month);
        $bankId = $request->bank_id;
        $customerId = $request->customer_id;
    
        $contracts = Contract::where('contract_status', 'ساري')
                        ->whereBetween('start_month', [$startDate, $endDate])
                        ->when($bankId, function ($query) use ($bankId) {
                            $query->where('bank_id', $bankId);
                        })
                        ->when($customerId, function ($query) use ($customerId) {
                            $query->where('customer_id', $customerId);
                        })
                        ->with(['customer', 'payments'])
                        ->get();
    
        $unpaidPayments = [];
        foreach ($contracts as $contract) {
            foreach ($contract->getMonthsArray() as $month) {
                $monthDate = Carbon::parse($month);
                if ($monthDate->between($startDate, $endDate)) {
                    $isPaid = $contract->payments()->whereMonth('month', $monthDate->month)
                                                   ->whereYear('month', $monthDate->year)
                                                   ->where('paid', true)
                                                   ->exists();
                    
                   dd($isPaid, $contract->payments, $contract->getMonthsArray());


                    if (!$isPaid) {
                        $unpaidPayments[] = [
                            'id' => $contract->id,
                            'customer' => $contract->customer->name,
                            "bank_number" => $contract->customer->bank_number,
                            'contract_description' => $contract->description,
                            'due_month' => $monthDate->format('Y-m'),
                            'monthly_deduction' => $contract->monthly_deduction,
                            'paid' => $contract->paid,
                            'due' => $contract->due,
                        ];
                    }
                }
            }
        }

        $unpaidPayments = collect($unpaidPayments);
        $totalDue = $unpaidPayments->sum('monthly_deduction');
        $totalPaid = $unpaidPayments->sum('paid');
        $totalRemaining = $unpaidPayments->sum('due');

        return view('statements.print', compact('unpaidPayments', 'totalDue', 'totalPaid', 'totalRemaining'));
    }
    
}
