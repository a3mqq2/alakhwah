@extends('layouts.app')

@section('title')
    عرض الزبائن
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header bg-primary text-light">البحث عن زبون</div>
    <div class="card-body">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">اسم الزبون:</label>
                        <input type="text" value="{{request('name')}}" class="form-control" name="name" id="name" value="{{ request('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="name"> رقم الحساب :</label>
                        <input type="text" value="{{request('bank_number')}}" class="form-control" name="bank_number" id="name" value="{{ request('bank_number') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="phone">رقم الهاتف:</label>
                        <input type="text" value="{{request('phone')}}" class="form-control" name="phone" id="phone" value="{{ request('phone') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="phone_2">رقم الهاتف الاحتياطي:</label>
                        <input type="text" value="{{request('phone_2')}}" class="form-control" name="phone_2" id="phone_2" value="{{ request('phone_2') }}">
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
            @forelse ($customers as $customer)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $customer->name }}</h5>
                            <p class="card-text"><strong>رقم الهاتف:</strong> {{ $customer->phone ?? '-' }}</p>
                            <p class="card-text"><strong>رقم الهاتف الاحتياطي:</strong> {{ $customer->phone_2 ?? '-' }}</p>
                            <p class="card-text"><strong>العنوان:</strong> {{ $customer->address ?? '-' }}</p>
                            <p class="card-text"><strong>المصرف :</strong> {{ $customer->bank ? $customer->bank->name : ''}}</p>
                            <p class="card-text"><strong>رقم الحساب المصرفي  :</strong> {{ $customer->bank_number}}</p>
                            <p class="card-text"><strong> رقم البطاقه الشخصيه :</strong> {{ $customer->identifier_number}}</p>
                            <div class="mt-3">
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-primary btn-sm"><i class="fe fe-eye"></i> عرض </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-info btn-sm"><i class="fe fe-edit"></i> تعديل </a>
                                {{-- <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_supplier_{{ $customer->id }}">
                                    <i class="fe fe-trash"></i>
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @empty 
                <div class="col-md-12 text-center">
                    <div class="img d-flex justify-content-center m-auto">
                        <img src="{{url('/logos/out.png')}}" width="120" alt="">
                    </div>
                    <p class="text-center font-weight-bold">لا يوجد أي زبائن</p>
                    <a href="{{route('customers.create')}}" class="btn btn-primary text-light btn-sm">إضافة زبون جديد</a>
                </div>
            @endforelse
        </div>
        @foreach ($customers as $supplier)
        <div class="modal fade" id="delete_supplier_{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="delete_supplier_{{ $supplier->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete_supplier_{{ $supplier->id }}Label">هل انت متأكد من الحذف ؟ </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('customers.destroy', $supplier) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <h5 class="text-center font-weight-bold mb-2">هل انت متأكد من الحذف ؟ </h5>
                            <button class="btn btn-danger btn-block"> حذف </button>
                            <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">إلغاء</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

        {{ $customers->appends($_GET)->links() }}
    </div>

</div>
@endsection