@extends('layouts.app')

@section('title')
    إنشاء زبون جديد
@endsection

@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="name">اسم الزبون</label>
                        <input type="text" name="name" value="{{ old('name') }}" required id="name" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required id="phone" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone_2">رقم هاتف إضافي (اختياري)</label>
                        <input type="text" name="phone_2" value="{{ old('phone_2') }}" id="phone_2" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="address">العنوان (اختياري)</label>
                        <input type="text" name="address" value="{{ old('address') }}" id="address" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bank_id"> المصرف </label>
                        <select name="bank_id" id="" class=" form-control">
                            <option value="">يرجى تحديد مصرف</option>
                            @foreach (App\Models\Bank::all() as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bank_number">رقم الحساب المصرفي</label>
                        <input type="text" name="bank_number" value="{{ old('bank_number') }}" id="bank_number" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="identifier_number"> البطاقه الشخصيه </label>
                        <input type="text" name="identifier_number" value="{{ old('identifier_number') }}" id="identifier_number" class="form-control">
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
