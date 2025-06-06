<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Customer;
use Barryvdh\DomPDF\PDF;
use App\Models\Statement;
use Illuminate\Http\Request;
use App\Imports\ContractsImport;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Maatwebsite\Excel\Facades\Excel;

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
            
            // تقسيم البيانات إلى مجموعات من 30 صفًا
            $chunks = array_chunk($contractsData[0], 30);
            $groupNumber = 1;
    
            foreach ($chunks as $chunk) {
                $groupIndex = 1; // إعادة تعيين الـ index لكل مجموعة جديدة
    
                foreach ($chunk as $contract_data) {
                    // رقم الصف داخل المجموعة

                    $rowIndex = $groupIndex;
    
                    // Filter out rows where bank_number or amount is missing


                    if (empty($contract_data[0]) || empty($contract_data[1]) || empty($contract_data[3]) || empty($contract_data[5]) || empty($contract_data[13]) || empty($contract_data[11]) || $contract_data[0] == "صافي القسط"  ) {
                        $groupIndex++;
                        continue; // Skip this iteration and move to the next row
                    }



                    // Pad the bank_number with leading zeros if it's less than 15 digits
                    $bank_number = str_pad($contract_data[5], 15, '0', STR_PAD_LEFT);
    
                    // Remove any commas from the amount before converting to float
                    $cleaned_amount = str_replace(',', '', $contract_data[0]);
    
                    $amount = floatval($cleaned_amount) - 5;
                    $total_price += $amount;
    
                    $customer = Customer::where('bank_number', $bank_number)->first();
    
                    if (!$customer) {
                        $errors->add('contract_' . $rowIndex, "لم يتم العثور على رقم الحساب في الصف رقم " . $rowIndex . ' (المجموعة ' . $groupNumber . ') رقم الحساب :  ' . $bank_number . ' القيمة :  ' . $contract_data[0]  . ' الاسم : ' . $contract_data[13]);
                    } else {
                        $targetMonth = Carbon::parse($request->month)->startOfMonth();
                        $contracts = $customer->contracts->whereIn('monthly_deduction', [$amount, $contract_data[0]]);
    
                        if (!$contracts->count()) {
                            $errors->add('contract_' . $rowIndex, "لم يتم العثور على العقد المربوط بالحساب في الصف رقم " . $rowIndex . ' (المجموعة ' . $groupNumber . ') رقم الحساب : ' . $contract_data[5] . ' القيمة  : ' . $contract_data[0] . ' الاسم : ' . $contract_data[13]);
                        } else {
                            $contracts = $customer->contracts()->whereIn('monthly_deduction', [$amount, $contract_data[0]])
                                ->whereDoesntHave('payments', function ($q) {
                                    $q->where('month', Carbon::parse(request('month')));
                                })->get();
                        }
    
                        if ($contracts->count()) {
                            $contract = $contracts->first();
    
                            $checkPayment = $contract->payments()->whereYear('month', Carbon::parse($request->month))->whereMonth('month', Carbon::parse($request->month))->get();
                            if ($checkPayment->count()) {
                                $errors->add('contract_' . $rowIndex, "تم الدفع المسبق للعقد التابع للصف رقم : " . $rowIndex . ' (المجموعة ' . $groupNumber . ')');
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
    
                    $groupIndex++; // Increment the index within the group
                }
    
                $groupNumber++; // Increment group number for the next chunk
            }
    
            $statement->update(['total_price' => $total_price]);
            DB::commit();
    
            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }
    
            return redirect()->back()->with('success', ['تم استيراد العقود بنجاح.']);
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

 

    public function generatePdf(Request $request)
    {
        // Retrieve the bank and month parameters from the request
        $month = $request->input('month');
        $bank_id = $request->input('bankName'); // Assuming the bank ID is passed as 'bankName'

        // Calculate the start and end of the month
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        // Fetch the contracts based on the bank ID and month
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

        // Check if each contract has a payment for the given month
        $contracts->each(function ($contract) use ($month) {
            $contract->is_payment = $contract->checkIfPayment($month);
        });

        // Prepare data for the PDF view
        $data = [
            'bankName' => $request->input('bankName'),
            'month' => $month,
            'contracts' => $contracts, // Pass the contracts to the view
            'today' => now()->format('Y-m-d'),
        ];

        // Generate the PDF using the prepared data
        $pdf = FacadePdf::loadView('pdf_template', $data);
        return $pdf->download('settlements.pdf');
    }
}
