@extends('layouts.app')

@section('title')
    إنشاء عقد جديد
@endsection

@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <form action="{{ route('contracts.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="customer_id">الزبون</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">يرجى تحديد الزبون</option>
                            @foreach (App\Models\Customer::orderByDesc('id')->get() as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="installments"> الأقساط</label>
                        <input type="number" name="installments" value="{{ old('installments') }}" required id="installments" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="monthly_deduction">قيمة الاستقطاع الشهري</label>
                        <input type="number" name="monthly_deduction" value="{{ old('monthly_deduction') }}" required id="monthly_deduction" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="start_month">شهر بدء الاستقطاع</label>
                        <input type="month" name="start_month" value="{{ old('start_month') }}" id="start_month" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description">الوصف</label>
                        <textarea rows="4" name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="notes">ملاحظات</label>
                        <textarea rows="4" name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3">
                            <button class="btn btn-primary">إنشاء</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
