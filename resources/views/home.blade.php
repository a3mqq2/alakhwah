@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">

          <div class="col-md-4 mb-4">
            <a href="{{route('customers.index')}}" class="link">
                <div class="card shadow">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <span class="h2 mb-0">{{App\Models\Customer::count()}}</span>
                          <p class="small text-muted mb-0">الزبائن</p>
                        </div>
                        <div class="col-auto">
                          <span class="fe fe-32 fe-users text-muted mb-0"></span>
                        </div>
                      </div>
                    </div>
                  </div>
              </a>
          </div>



          <div class="col-md-4 mb-4">
            <a href="{{route('contracts.index', ['contract_status' => "ساري"])}}" class="link">
                <div class="card bg-warning">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <span class="h2 text-white mb-0">{{App\Models\Contract::where('contract_status', 'ساري')->count()}}</span>
                          <p class="small text-white mb-0">العقود الساريه</p>
                        </div>
                        <div class="col-auto">
                          <span class="fe fe-32 fe fe-file-text text-white mb-0"></span>
                        </div>
                      </div>
                    </div>
                  </div>
              </a>
          </div>

          <div class="col-md-4 mb-4">
            <a href="{{route('contracts.index', ['contract_status' => "مكتمل"])}}" class="link">
                <div class="card bg-success">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <span class="h2 text-white mb-0">{{App\Models\Contract::where('contract_status', 'مكتمل')->count()}}</span>
                          <p class="small text-white mb-0">العقود المكتمله</p>
                        </div>
                        <div class="col-auto">
                          <span class="fe fe-32 fe fe-file-text text-white mb-0"></span>
                        </div>
                      </div>
                    </div>
                  </div>
              </a>
          </div>

          


       
    
          <div class="col-md-4 mt-1 mb-2">
            <a href="{{route('contracts.create')}}" class="btn btn-info btn-block"> اضافه عقد جديد<i class="fe fe-file"></i></a>
          </div>

          <div class="col-md-4 mt-1 mb-2">
            <a href="{{route('customers.create')}}" class="btn btn-success btn-block"> اضافه زبون جديد<i class="fe fe-user"></i></a>
          </div>


          <div class="col-md-4 mt-1 mb-2">
            <a href="{{route('statements.create')}}" class="btn btn-danger btn-block"> اضافه  اقساط جديدة<i class="fe fe-credit-card"></i></a>
          </div>

          

        </div>

        
    </div>
</div>
@endsection