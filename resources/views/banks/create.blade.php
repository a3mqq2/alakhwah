@extends('layouts.app')

@section('title')
    إنشاء حساب مصرف جديد
@endsection

@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <form action="{{ route('banks.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-12">
                        <label for="name">اسم المصرف</label>
                        <input type="text" name="name" value="{{ old('name') }}" required id="name" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label for="name"> رقم الحساب </label>
                        <input type="text" name="number" value="{{ old('number') }}" required id="number" class="form-control">
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
