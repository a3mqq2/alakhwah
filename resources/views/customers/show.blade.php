@extends('layouts.app')

@section('title')
    عرض الزبون
@endsection

@section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">تفاصيل الزبون</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="bg-light text-center py-2 border">الاسم: {{ $customer->name }} </p>
                            </div>
                            <div class="col-6">
                                <p class="bg-light text-center py-2 border">رقم الهاتف:</p>
                                <p class="text-center border p-2">{{ $customer->phone }}</p>
                            </div>
                            <div class="col-6">
                                <p class="bg-light text-center py-2 border">رقم الهاتف البديل:</p>
                                <p class="text-center border p-2">{{ $customer->phone_2 ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="bg-light text-center py-2 border">العنوان:</p>
                                <p class="text-center border p-2">{{ $customer->address??'-' }}</p>
                            </div>
                            <div class="col-6">
                                <p class="bg-light text-center py-2 border">اسم البنك:</p>
                                <p class="text-center border p-2">{{ $customer->bank->name }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="bg-light text-center py-2 border">رقم الحساب البنكي:</p>
                                <p class="text-center border p-2">{{ $customer->bank_number }}</p>
                            </div>
                            <div class="col-6">
                                <p class="bg-light text-center py-2 border">الرقم التعريفي:</p>
                                <p class="text-center border p-2">{{ $customer->identifier_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">اجماليات</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light text-center">اجمالي القيمه 

                                            </th>
                                            <th class="text-center">{{(float)$customer->contracts()->sum('installments')}} د.ل </th>
                                        </tr>
                                        <tr>
                                            <th class="bg-success text-light text-center">اجمالي المدفوع</th>
                                            <th class="text-center">{{$customer->contracts()->sum('paid')}} د.ل </th>
                                        </tr>
                                        <tr>
                                            <th class="bg-danger text-light text-center">اجمالي المتبقي</th>
                                            <th class="text-center">{{$customer->contracts()->sum('due')}} د.ل </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header bg-primary text-light">العقود</div>
                    <div class="card-body">
                        @foreach ($customer->contracts as $contract)
                        <div class="col-12">
                            <p class="bg-light text-priamry font-weight-bold text-center pl-3 py-2 border"> #{{$contract->id}} {{$contract->description}} </p>
                            <p class="text-center border p-3 text-left"> 
                                <span><strong>الأقساط : </strong> {{ $contract->installments }} د.ل </span> <br>
                                <span class="mt-4"><strong> قيمة الاستقطاع الشهري : </strong> {{ $contract->monthly_deduction }} د.ل </span> <br>
                                <span class="mt-4"><strong> شهر البدء : </strong> {{ $contract->start_month }} </span> <br>
                                <span class="mt-4"><strong> شهر الانتهاء : </strong> {{ date('Y-m', strtotime($contract->end_month)) }} </span> <br>
                                <span class="mt-4"><strong> عدد الاشهر : </strong> {{ $contract->months_count }}  شهر/شهور </span> <br>
                                <span class="mt-4"><strong> الحاله : </strong>  @if ($contract->contract_status == "ساري")
                                    <span class="badge badge-warning">{{$contract->contract_status}}</span>
                                    @elseif($contract->contract_status == "مكتمل")
                                    <span class="badge badge-success">{{$contract->contract_status}}</span>
                                    @else 
                                    <span class="badge badge-danger">{{$contract->contract_status}}</span>
                                @endif</span> <br>

                                <span class="mt-4"><strong> المدفوع : </strong> {{ $contract->paid }}  د.ل </span> <br>
                                <span class="mt-4"><strong> المتبقي : </strong> {{ $contract->due }}  د.ل </span> <br>
                                <span class="mt-4"><strong> ملاحظات : </strong> {{ $contract->notes }}  </span> <br>
                                    
                                <a href="{{route('contracts.show', $contract)}}" class="btn btn-primary mt-3 text-light btn-sm"> عرض العقد <i class="fe fe-eye"></i></a>
                                @if ($contract->contract_status != "ملغي")
                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')"><i class="fe fe-times"></i> إلغاء العقد </button>
                                        </form>
                                @endif
                                <form action="{{ route('contracts.destroy', ['contract' => $contract->id, 'delete' => 1]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')"><i class="fe fe-trash"></i> حذف  </button>
                                </form>
                            </p>
                        </div>
                        <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
