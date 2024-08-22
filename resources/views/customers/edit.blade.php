@extends('layouts.app')

@section('title')
    تعديل زبون
@endsection

@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="name">اسم الزبون</label>
                        <input type="text" name="name" value="{{ $customer->name }}" required id="name" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ $customer->phone }}" required id="phone" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone_2">رقم هاتف إضافي (اختياري)</label>
                        <input type="text" name="phone_2" value="{{ $customer->phone_2 }}" id="phone_2" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="address">العنوان (اختياري)</label>
                        <input type="text" name="address" value="{{ $customer->address }}" id="address" class="form-control">
                    </div>
                    <!-- الحقول الجديدة -->
                    <div class="col-md-4 mb-3">
                        <label for="bank_id"> المصرف </label>
                        <select name="bank_id" id="bank_id" class="form-control">
                            <option value="">يرجى تحديد مصرف</option>
                            @foreach (App\Models\Bank::all() as $bank)
                                <option value="{{ $bank->id }}" @if($bank->id == $customer->bank_id) selected @endif>{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bank_number">رقم الحساب المصرفي</label>
                        <input type="text" name="bank_number" value="{{ $customer->bank_number }}" id="bank_number" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="identifier_number"> البطاقة الشخصية </label>
                        <input type="text" name="identifier_number" value="{{ $customer->identifier_number }}" id="identifier_number" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3">
                            <button class="btn btn-primary">تحديث</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
