@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mt-3"> عرض المدفوعات </h2>

      <div class="card mb-3">
        <div class="card-header bg-primary text-light">فلتره</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <form action="{{ route('payments.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">الشهر</label>
                                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="">المصرف </label>
                                <select name="bank" class="form-select form-control">
                                    <option value="">اختر المصرف </option>
                                    @foreach ($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">بحث</button>

                    </form>
                </div>
            </div>
        </div>
      </div>

        <div class="row">
            @foreach($payments as $payment)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">العقد: {{ $payment->contract->description }}</h5>
                            <p class="card-text">الزبون: {{ $payment->customer->name }}</p>
                            <p class="card-text">الشهر: {{ date('Y-m', strtotime($payment->month)) }}</p>
                            <p class="card-text">المبلغ: {{ $payment->amount }}</p>
                            <p class="card-text">المدفوع: {{ $payment->paid }}</p>
                            <p class="card-text">المتبقي: {{ $payment->due }}</p>
                            <p class="card-text">الملاحظات: {{ $payment->notes }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $payments->appends(request()->input())->links() }}
    </div>
@endsection
