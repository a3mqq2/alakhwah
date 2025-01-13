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


        if($request->filled('id')) {
            $query->where('id', $request->id);
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
            'monthly_deduction' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'start_month' => 'required|date_format:Y-m',
            'notes' => 'nullable|string|max:255',
        ]);
    
        // التحقق من أن القسمة لا تحتوي على كسر
        if ($request->installments % $request->monthly_deduction !== 0) {
            return redirect()->back()->withErrors([
                'installments' => 'يجب أن يكون ناتج قسمة قيمة الأقساط على قيمة الاستقطاع الشهري عددًا صحيحًا.'
            ]);
        }
    
        // حساب عدد الأشهر
        $months_count = $request->installments / $request->monthly_deduction; 
    
        // التحقق من أن عدد الأشهر لا يقل عن 1
        if ($months_count < 1) {
            return redirect()->back()->withErrors([
                'installments' => 'عدد الأشهر المحسوب غير صحيح، تحقق من قيمة الأقساط وقيمة الاستقطاع الشهري.'
            ]);
        }
    
        // حساب تاريخ نهاية العقد
        $end_month = Carbon::parse($request->start_month)->addMonths($months_count - 1); 
    
        // تحديد حالة العقد بناءً على تاريخ النهاية
        $status = $end_month->lessThan(Carbon::now()) ? "مكتمل" : "ساري";
    
        // الحصول على بيانات العميل
        $customer = Customer::findOrFail($request->customer_id);
    
        // إنشاء العقد
        $contract = Contract::create([
            'customer_id' => $request->input('customer_id'),
            'bank_id' => $customer->bank_id,
            'installments' => $request->input('installments'),
            'monthly_deduction' => $request->input('monthly_deduction'),
            'description' => $request->input('description'),
            'start_month' => Carbon::parse($request->start_month)->format('Y-m-d'),
            'end_month' => $end_month->format('Y-m-d'),
            'months_count' => $months_count,
            'contract_status' => $status,
            'cancellation_reason' => $request->input('cancellation_reason'),
            'notes' => $request->input('notes'),
            'paid' => 0,
            'due' => $request->installments,
        ]);
    
        return redirect()->route('contracts.index', ['id' => $contract->id])
            ->with('success', ['تم إنشاء العقد بنجاح.']);
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
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'installments' => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'start_month' => 'required|date_format:Y-m',
            'notes' => 'nullable|string|max:255',
        ]);

        // العثور على العقد المطلوب
        $contract = Contract::findOrFail($id);

        // التحقق من أن الأقساط تقبل القسمة على الاستقطاع الشهري بدون كسور
        if ($request->installments % $request->monthly_deduction !== 0) {
            return redirect()->back()->withErrors([
                'installments' => 'يجب أن يكون ناتج قسمة قيمة الأقساط على قيمة الاستقطاع الشهري عددًا صحيحًا.'
            ]);
        }

        // حساب عدد الأشهر
        $months_count = $request->installments / $request->monthly_deduction; 

        // التحقق من أن عدد الأشهر لا يقل عن 1
        if ($months_count < 1) {
            return redirect()->back()->withErrors([
                'installments' => 'عدد الأشهر المحسوب غير صحيح، تحقق من قيمة الأقساط وقيمة الاستقطاع الشهري.'
            ]);
        }

        // حساب تاريخ نهاية العقد بدقة
        $end_month = Carbon::parse($request->start_month)->addMonths($months_count - 1); 

        // تحديد حالة العقد بناءً على تاريخ النهاية
        $status = $end_month->lessThan(Carbon::now()) ? "مكتمل" : "ساري";

        // تحديث بيانات العقد
        $contract->update([
            'customer_id' => $request->input('customer_id'),
            'installments' => $request->input('installments'),
            'monthly_deduction' => $request->input('monthly_deduction'),
            'description' => $request->input('description'),
            'start_month' => $request->input('start_month'),
            'end_month' => $end_month->format('Y-m'),
            'months_count' => $months_count,
            'contract_status' => $status,
            'cancellation_reason' => $request->input('cancellation_reason'),
            'notes' => $request->input('notes'),
            'due' => $request->installments,
        ]);

        return redirect()->route('contracts.index', ['id' => $contract->id])
            ->with('success', ['تم تحديث العقد بنجاح.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,Request $request)
    {
        $contract = Contract::findOrFail($id);
        if($request->delete) 
        {
            $contract->delete();
        } else {
            $contract->contract_status = "ملغي";
            $contract->save();
    
        }
     
        return redirect()->route('contracts.index')->with('success', ['تم حذف العقد بنجاح.']);
    }

    public function print(Contract $contract) {
        return view('contracts.print', compact('contract'));
    }
}
