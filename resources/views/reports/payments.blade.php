
@extends('layouts.app')
@section('content')
@section('title')
        عرض الكشف من شهر {{request('from_month')}} الى شهر : {{request('to_month')}}

        @if (request('customer_id'))
           <br> للزبون : {{$customer->name}}
        @endif
@endsection
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
                    @foreach ($payments as $payment)
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


