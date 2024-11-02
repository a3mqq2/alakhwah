@extends('layouts.app')

@section('title')
    عرض تفاصيل العقد
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('contracts.print', $contract->id) }}" class="btn btn-secondary btn-sm"><i class="fe fe-print"></i> طباعة   </a>
        @if ($contract->contract_status != "ملغي")
        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')"><i class="fe fe-trash"></i> إلغاء العقد </button>
        </form>
    @endif
    </div>
</div>
    <div class="card">
        <div class="card-header bg-primary text-light">تفاصيل العقد</div>
        <div class="card-body">
            <h5 class="card-title">وصف العقد: {{ $contract->description }}</h5>
            <p class="card-text"><strong> الزبون :</strong> {{ $contract->customer ? $contract->customer->name : '' }}</p>
            <p class="card-text"><strong> رقم  الهاتف :</strong> {{ $contract->customer ? $contract->customer->phone : '' }}</p>
            <p class="card-text"><strong> رقم الحساب المصرفي  :</strong> {{ $contract->customer ? $contract->customer->bank_number : '' }}</p>
            <p class="card-text"><strong> رقم البطاقه الشخصيه :</strong> {{ $contract->customer ? $contract->customer->identifier_number : '' }}</p>
            <p class="card-text"><strong>  المصرف :</strong> {{ $contract->bank ? $contract->bank->name : '' }}</p>
            <p class="card-text"><strong> الأقساط:</strong> {{ $contract->installments }}</p>
            <p class="card-text"><strong>الاستقطاع الشهري:</strong> {{ $contract->monthly_deduction }}</p>
            <p class="card-text"><strong>بداية العقد:</strong> {{ $contract->start_month }}</p>
            <p class="card-text"><strong>نهاية العقد:</strong> {{ date('Y-m', strtotime($contract->end_month)) }}</p>
            <p class="card-text"><strong>حالة العقد:</strong>
                @if ($contract->contract_status == "ساري")
                    <span class="badge badge-warning">{{$contract->contract_status}}</span>
                @elseif($contract->contract_status == "مكتمل")
                    <span class="badge badge-success">{{$contract->contract_status}}</span>
                @else 
                    <span class="badge badge-danger">{{$contract->contract_status}}</span>
                @endif
            </p>
            <p class="card-text"><strong>عدد الأشهر:</strong> {{ $contract->months_count }}</p>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header bg-light text-primary">المدفوعات</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="bg-primary" scope="col">#</th>
                        <th class="bg-primary" scope="col">الشهر</th>
                        <th class="bg-primary" scope="col">القيمة </th>
                        <th class="bg-primary" scope="col"> اجمالي المدفوع </th>
                        <th class="bg-primary" scope="col"> اجمالي المتبقي </th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($contract->payments as $payment)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{date('Y-m', strtotime($payment->month))}}</td>
                                <td>{{$payment->amount}} د.ل </td>
                                <td>{{$payment->paid}} د.ل </td>
                                <td>{{$payment->due}} د.ل </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
