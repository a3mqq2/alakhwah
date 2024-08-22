@extends('layouts.app')

@section('title')
    عرض العقود
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header bg-primary text-light">البحث عن عقد</div>
    <div class="card-body">
        <form method="GET" action="{{ route('contracts.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="description">وصف العقد:</label>
                        <input type="text" value="{{ request('description') }}" class="form-control" name="description" id="description">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_month"> بدايه الاستقطاع :</label>
                        <input type="month" value="{{ request('start_month') }}" class="form-control" name="start_month" id="start_month">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="customer">اسم الزبون:</label>
                        <select class="form-control" name="customer" id="customer">
                            <option value="" selected> الكل </option>
                            @foreach(App\Models\Customer::all() as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="bank"> المصرف :</label>
                        <select class="form-control" name="bank" id="bank">
                            <option value="" selected>الكل</option>
                            @foreach(App\Models\Bank::all() as $bank)
                                <option value="{{ $bank->id }}" {{ request('bank') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary"> بحث <i class="fa fa-search"></i> </button>
                </div>
            </div>
        </form>
    </div>
</div>


    <div class="card">
        <div class="card-body">
            <div class="row">
                @forelse ($contracts as $contract)
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $contract->description }}</h5>
                                <p class="card-text"><strong> الزبون :</strong> {{ $contract->customer ? $contract->customer->name : '' }}</p>
                                <p class="card-text"><strong> المصرف :</strong> {{ $contract->bank ? $contract->bank->name : '' }}</p>
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
                                <div class="mt-3">
                                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-info btn-sm"><i class="fe fe-eye"></i> عرض </a>
                                    <a href="{{ route('contracts.print', $contract->id) }}" class="btn btn-secondary btn-sm"><i class="fe fe-print"></i> طباعة   </a>
                                    <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')"><i class="fe fe-trash"></i> حذف </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 text-center">
                        <div class="col-md-12 text-center">
                            <div class="img d-flex justify-content-center m-auto">
                                <img src="{{url('/logos/out.png')}}" width="120" alt="">
                            </div>
                            <p class="text-center font-weight-bold">لا يوجد أي عقود</p>
                            <a href="{{route('contracts.create')}}" class="btn btn-primary text-light btn-sm">إضافة عقد جديد</a>
                        </div>
                    </div>
                @endforelse
            </div>
            {{ $contracts->links() }}
        </div>
    </div>
    <script>
        // Check if "id" parameter exists in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const idParam = urlParams.get('id');
    
        // If "id" parameter exists, open a new window with the print URL
        if (idParam) {
            const printUrl = `/contracts/${idParam}/print`;
            window.open(printUrl, '_blank');
        }
    </script>
@endsection
