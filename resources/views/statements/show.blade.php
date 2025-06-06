@extends('layouts.app')

@section('title')
    عرض كشف الاقساط لشهر {{date('Y-m', strtotime($statement->month))}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-primary text-center">رقم الإدخال</th>
                                    <td class="text-center">{{$statement->id}}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary text-center">المصرف</th>
                                    <td class="text-center">{{$statement->bank ? $statement->name : "-"}}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary text-center">يوم الإدخال</th>
                                    <td class="text-center">{{date('Y-m-d H:i', strtotime($statement->created_at))}}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary text-center"> اجمالي المدفوع </th>
                                    <td class="text-center">{{$statement->total_price}} د.ل </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-header bg-light text-primary">المدفوعات</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th class="bg-primary" scope="col">#</th>
                                <th class="bg-primary">اسم صاحب الحساب</th>
                                <th class="bg-primary text-light">رقم الحساب</th>
                                <th class="bg-primary" scope="col">القيمة </th>
                                <th class="bg-primary" scope="col"> اجمالي المدفوع </th>
                                <th class="bg-primary" scope="col"> اجمالي المتبقي </th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($statement->payments as $payment)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$payment->customer ? $payment->customer->name : ""}}</td>
                                        <td>{{$payment->customer ? $payment->customer->bank_number : ""}}</td>
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
        </div>
    </div>
@endsection
