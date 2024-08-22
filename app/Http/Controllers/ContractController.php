<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::query();
    
        // Filter by contract description
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }
    
        // Filter by start month
        if ($request->filled('start_month')) {
            $query->where('start_month', '=', $request->input('start_month'));
        }
    
        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customer_id', '=', $request->input('customer'));
        }
        

        if($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }


        if($request->filled('contract_status')) {
            $query->where('contract_status', $request->contract_status);
        }

        $contracts = $query->orderByDesc('id')->paginate(12);
    
        return view('contracts.index', compact('contracts'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contracts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'installments' => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'start_month' => 'required|date_format:Y-m',
            'notes' => 'nullable|string|max:255',
        ]);

        $status = '';
        $months_count = round($request->installments/$request->monthly_deduction);
        $end_month = Carbon::parse($request->start_month)->addMonths($months_count);
        if(Carbon::parse($request->end_month)->month < Carbon::now()->month) {
            $status = "مكتمل";
        } else {
            $status = "ساري";
        }
        $customer = Customer::findOrFail($request->customer_id);
        $contract = Contract::create([
            'customer_id' => $request->input('customer_id'),
            'bank_id' => $customer->bank_id,
            'installments' => $request->input('installments'),
            'monthly_deduction' => $request->input('monthly_deduction'),
            'description' => $request->input('description'),
            'start_month' => $request->input('start_month'),
            'end_month' => $end_month,
            'months_count' => $months_count,
            'contract_status' => $status,
            'cancellation_reason' => $request->input('cancellation_reason'),
            'notes' => $request->input('notes'),
            'paid' => 0,
            'due' => $request->installments,
        ]);

        return redirect()->route('contracts.index', ['id' => $contract->id])->with('success', [ 'تم إنشاء العقد بنجاح.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contract = Contract::findOrFail($id);
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contract = Contract::findOrFail($id);
        return view('contracts.edit', compact('contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'installments' => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'start_month' => 'required|date_format:Y-m',
            'end_month' => 'required|date_format:Y-m|after:start_month',
            'cancellation_reason' => 'nullable|required_if:contract_status,cancelled|string|max:255',
            'notes' => 'nullable|string|max:255',
            'months_count' => 'required|numeric|min:1',
        ]);

        $contract = Contract::findOrFail($id);
        $customer = Customer::findOrFail($request->customer_id);
        $contract->update([
            'customer_id' => $request->input('customer_id'),
            'bank_id' => $customer->bank_id,
            'installments' => $request->input('installments'),
            'monthly_deduction' => $request->input('monthly_deduction'),
            'description' => $request->input('description'),
            'start_month' => $request->input('start_month'),
            'end_month' => $request->input('end_month'),
            'cancellation_reason' => $request->input('cancellation_reason'),
            'notes' => $request->input('notes'),
            'paid' => 0,
            'due' => $request->installments,
        ]);

        return redirect()->route('contracts.index')->with('success', ['تم تحديث العقد بنجاح.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return redirect()->route('contracts.index')->with('success', ['تم حذف العقد بنجاح.']);
    }

    public function print(Contract $contract) {
        return view('contracts.print', compact('contract'));
    }
}
