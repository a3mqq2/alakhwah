<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Statement;
use Illuminate\Http\Request;
use App\Imports\ContractsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\MessageBag;

class StatementController extends Controller
{
 

    public function index() {
        $statements = Statement::orderByDesc('id')->get();
        return view('statements.index', compact('statements'));
    }
    public function create() {
        return view('statements.create');
    }



    public function show(Statement $statement) {
        return view('statements.show', compact('statement'));
    }


    public function print(Statement $statement) {
        return view('statements.print', compact('statement'));
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "contracts" => "required",
            "bank_id" => "required",
            "month" => "required",
        ]);

        try {
            DB::beginTransaction();
            $errors = [];
            $statement = new Statement();
            $statement->month = Carbon::parse($request->month);
            $statement->total_price = 0;
            $statement->notes = $request->notes;
            $statement->bank_id = $request->bank_id;
            $statement->save();
            $total_price = 0;
            foreach(json_decode($request->contracts) as $index=>$contract_data) {
                $index = $index+1;
                $amount = $contract_data->amount - 5;
                $total_price += $amount;
                $customer = Customer::where('bank_number', $contract_data->bank_number)->first();

                if(!$customer) {
                    $errors[] = "لم يتم العثور على رقم الحساب في الصف رقم " . $index;
                } else {
                    $targetMonth = Carbon::parse($request->month)->startOfMonth();

                    $contracts = $customer->contracts->whereIn('monthly_deduction', [$amount, $contract_data->amount]);



                    if(!$contracts->count()) {
                        $errors[] = "لم يتم العثور على  العقد المربوط بالحساب في الصف رقم " . $index;
                    } else {
                        $contracts = $customer->contracts()->whereIn('monthly_deduction', [$amount, $contract_data->amount])->whereDoesntHave('payments', function($q) {
                            $q->where('month', Carbon::parse(request('month')));
                        })->get();
                    }




                  


                    if($contracts->count()) {
                        $contract = $contracts->first();

                        $checkPayment = $contract->payments()->whereYear('month', Carbon::parse($request->month))->whereMonth('month', Carbon::parse($request->month))->get();
                        if($checkPayment->count()) {
                            $errors[] = "تم الدفع المسبق للعقد التابع للصف رقم : " . $index;
                        } else {

                            $contract->decrement('due', $contract->monthly_deduction);
                            $contract->increment('paid', $contract->monthly_deduction);


                            $payment = new Payment();
                            $payment->contract_id = $contract->id;
                            $payment->customer_id = $contract->customer_id;
                            $payment->month = Carbon::parse($request->month);
                            $payment->paid = $contract->paid;
                            $payment->due = $contract->due;
                            $payment->amount = $contract->monthly_deduction;
                            $payment->statement_id = $statement->id;
                            $payment->save();

                            $contract->save();
                        }
                    }
                }
               

               
            }

            $statement->update(['total_price' => $total_price]);
            if(count($errors)) {
                return response()->json($errors, 422);
            }

            DB::commit();
            return response()->json([], 200);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json([$e->getMessage()], 442);
        }
    }


    public function store_excel(Request $request)
    {
        $request->validate([
            "contracts_file" => "required|file|mimes:xlsx,xls",
            "bank_id" => "required",
            "month" => "required",
        ], [
            'contracts_file.required' => 'يجب اختيار ملف Excel يحتوي على العقود.',
            'contracts_file.mimes' => 'يجب أن يكون الملف من نوع Excel (xls, xlsx).',
            'bank_id.required' => 'يجب اختيار المصرف.',
            'month.required' => 'يجب اختيار الشهر.'
        ]);

        try {
            DB::beginTransaction();
            $errors = new MessageBag();
            $statement = new Statement();
            $statement->month = Carbon::parse($request->month);
            $statement->total_price = 0;
            $statement->notes = $request->notes;
            $statement->bank_id = $request->bank_id;
            $statement->save();
            $total_price = 0;

            $contractsData = Excel::toArray(new ContractsImport, $request->file('contracts_file'));

            foreach ($contractsData[0] as $index => $contract_data) {
                $index = $index + 1;

                // Filter out rows where bank_number or amount is missing
                if (empty($contract_data['bank_number']) || empty($contract_data['amount'])) {
                    continue; // Skip this iteration and move to the next row
                }

                if($index==7) {
                    dd($contract_data);
                }

                $amount = floatval($contract_data['amount']) - 5;
                $total_price += $amount;

                $customer = Customer::where('bank_number', 'like', '%' . $contract_data['bank_number'] . '%')->first();

                if (!$customer) {
                    $errors->add('contract_'.$index, "لم يتم العثور على رقم الحساب في الصف رقم " . $index . ' رقم الحساب :  ' . $contract_data['bank_number'] . ' القيمة :  ' . $contract_data['amount']);
                } else {
                    $targetMonth = Carbon::parse($request->month)->startOfMonth();
                    $contracts = $customer->contracts->whereIn('monthly_deduction', [$amount, $contract_data['amount']]);

                    if (!$contracts->count()) {
                        $errors->add('contract_'.$index, "لم يتم العثور على العقد المربوط بالحساب في الصف رقم " . $index);
                    } else {
                        $contracts = $customer->contracts()->whereIn('monthly_deduction', [$amount, $contract_data['amount']])
                            ->whereDoesntHave('payments', function($q) {
                                $q->where('month', Carbon::parse(request('month')));
                            })->get();
                    }

                    if ($contracts->count()) {
                        $contract = $contracts->first();

                        $checkPayment = $contract->payments()->whereYear('month', Carbon::parse($request->month))->whereMonth('month', Carbon::parse($request->month))->get();
                        if ($checkPayment->count()) {
                            $errors->add('contract_'.$index, "تم الدفع المسبق للعقد التابع للصف رقم : " . $index);
                        } else {
                            $contract->decrement('due', $contract->monthly_deduction);
                            $contract->increment('paid', $contract->monthly_deduction);

                            $payment = new Payment();
                            $payment->contract_id = $contract->id;
                            $payment->customer_id = $contract->customer_id;
                            $payment->month = Carbon::parse($request->month);
                            $payment->paid = $contract->paid;
                            $payment->due = $contract->due;
                            $payment->amount = $contract->monthly_deduction;
                            $payment->statement_id = $statement->id;
                            $payment->save();

                            $contract->save();
                        }
                    }
                }
            }

            $statement->update(['total_price' => $total_price]);
            DB::commit();

            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }

            return redirect()->back()->with('success', 'تم استيراد العقود بنجاح.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()]);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statement $statement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statement $statement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statement $statement)
    {
        //
    }

 
}
