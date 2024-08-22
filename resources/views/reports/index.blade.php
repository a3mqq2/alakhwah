@extends('layouts.app')
@section('content')
@section('title')
    إنشاء كشف جديد
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">حدد المدخلات</div>
            <div class="card-body">
                <form action="{{route('reports.payments')}}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">الزبون</label>
                                <select name="customer_id" id="" class="select2 form-control">
                                    <option value="">الكل</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">من شهر</label>
                            <input type="month" required name="from_month" id="" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="">الى شهر</label>
                            <input type="month" required name="to_month" id="" class="form-control">
                        </div>
                        <div class="col-md-12 mt-4">
                            <button class="btn btn-primary text-light">عرض</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
      <div class="card border-primary bg-light  text-center p-3">
          <h4 class="text-center text-primary m-0">الاجماليات</h4>
      </div>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-md-4 mb-4">
      <a  class="link">
          <div class="card bg-primary">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="h2 text-white mb-0">{{(float)$total_price}} د.ل </span>
                    <p class="small text-white mb-0"> القيمة الكلية للعقود </p>
                  </div>
                  <div class="col-auto">
                    <span class="fe fe-32 fe fe-money text-white mb-0"></span>
                  </div>
                </div>
              </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-4">
      <a  class="link">
          <div class="card bg-success">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="h2 text-white mb-0">{{(float)$total_paid}} د.ل </span>
                    <p class="small text-white mb-0"> القيمة  المدفوعة </p>
                  </div>
                  <div class="col-auto">
                    <span class="fe fe-32 fe fe-money text-white mb-0"></span>
                  </div>
                </div>
              </div>
            </div>
        </a>
    </div>


    <div class="col-md-4 mb-4">
      <a  class="link">
          <div class="card bg-danger">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="h2 text-white mb-0">{{(float)$total_due}} د.ل </span>
                    <p class="small text-white mb-0"> القيمة  المتبقية </p>
                  </div>
                  <div class="col-auto">
                    <span class="fe fe-32 fe fe-money text-white mb-0"></span>
                  </div>
                </div>
              </div>
            </div>
        </a>
    </div>


    <div class="col-md-4 mb-4">
      <a  class="link">
          <div class="card bg-warning">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="h2 text-white mb-0">{{(float)$bank_tax}} د.ل </span>
                    <p class="small text-white mb-0">  عمولة المصرف   </p>
                  </div>
                  <div class="col-auto">
                    <span class="fe fe-32 fe fe-money text-white mb-0"></span>
                  </div>
                </div>
              </div>
            </div>
        </a>
    </div>


  </div>
@endsection