@extends('layouts.app')

@section('title')
    استيراد العقود من ملف Excel
@endsection

@section('content')
    <div class="card mt-2">
        <div class="card-body">
          

            <form action="{{ route('contracts.store_excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-12">
                        <label for="contracts_file">اختر ملف Excel للعقود</label>
                        <input type="file" name="contracts_file" required id="contracts_file" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label for="bank_id">اختر المصرف</label>
                        @php
                            $banks = App\Models\Bank::all();
                        @endphp
                        <select name="bank_id" id="bank_id" class="form-control" required>
                            @foreach ($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="month">اختر الشهر</label>
                        <input type="month" name="month" required id="month" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3">
                            <button class="btn btn-primary">استيراد</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
