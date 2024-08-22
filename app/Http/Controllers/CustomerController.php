<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Bank;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::orderByDesc('id');
    
        // Apply filters if provided
        if ($request->name) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if ($request->bank_number) {
            $query->where('bank_number', request('bank_number'));
        }


    
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }
    
        if ($request->filled('phone_2')) {
            $query->where('phone_2', 'like', '%' . $request->input('phone_2') . '%');
        }
    
        // Add more filters if needed
    
        $customers = $query->paginate(10);
    
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
            "phone" => "required",
            "phone_2" => "nullable",
            "address" => "nullable",
            'bank_number' => 'required',
            'identifier_number' => 'required',
            'bank_id' => 'required',
        ]);


        $customer = Customer::where('name', $request->name)->first();
        if($customer) {
            return redirect()->back()->withInput()->withErrors(['يبدو ان هذا الزبون تم تسجيله من قبل']);
        }

        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', ['تم إنشاء زبون بنجاح']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            "name" => "required",
            "phone" => "required",
            "phone_2" => "nullable",
            "address" => "nullable",
            'bank_number' => 'required',
            'identifier_number' => 'required',
            'bank_id' => 'required',
        ]);

        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', ['تم تحديث الزبون بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', ['تم حذف الزبون بنجاح']);
    }

    public function get_customers(Bank $bank) {
        $customers = Customer::where('bank_id', $bank->id)
        ->whereHas('contracts', function($q) {
            $q->where('contract_status', 'ساري');
        })->orderByDesc('id')->get();

        return response()->json(['customers' => $customers]);
    }

    public function get_contracts(Customer $customer) {
        $requestedMonth = Carbon::parse(request('month'))->startOfMonth(); // Parse and start of requested month
        $endOfMonth = $requestedMonth->endOfMonth(); // End of requested month
    
        $contracts = Contract::where('customer_id', $customer->id)
            ->where('contract_status', 'ساري')
            ->with('payments')->get();
    
        return response()->json(['contracts' => $contracts]);
    }
    


    
}
