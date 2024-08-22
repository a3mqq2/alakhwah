<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Contract;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        if($request->month) {
            $query->where('month', $request->month);
        }

        if($request->bank_id) {
            $query->where('bank_id', $request->bank_id);
        }


        $banks = Bank::all();

        $payments = $query->orderByDesc('id')->paginate(10);
        return view('payments.index', compact("payments", 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "contract_id" => "required",
            "month" => "required",
            "amount" => "required",
        ]);
        try {

            $checkIfPayment = Payment::where('contract_id', $request->contract_id)
            ->whereMonth('month', Carbon::parse($request->month)->month)
            ->whereYear('month', Carbon::parse($request->month)->year)->first();

  

            DB::beginTransaction();
            $contract = Contract::findOrFail($request->contract_id);
            $contract->decrement('due', $request->amount);
            $contract->increment('paid', $request->amount);
            $payment = new Payment();
            $payment->contract_id = $request->contract_id;
            $payment->customer_id = $contract->customer_id;
            $payment->month = \Carbon\Carbon::parse($request->month);
            $payment->is_bank_fee = ($request->is_bank_fee) ? true : false;
            $payment->amount = $request->amount;
            $payment->paid = $contract->paid;
            $payment->due = $contract->due;
            $payment->notes = $request->notes;
            $payment->save();


            if($contract->paid >= $contract->installments) {
                $contract->update(['status' => "مكتمل"]);
            }

            DB::commit();
            return response()->json([], 200);
        } catch(\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return response()->json([], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function init() {
        $banks = Bank::all();
        return response()->json(['banks' => $banks], 200);
    }
}
