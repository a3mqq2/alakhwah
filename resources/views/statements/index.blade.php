@extends('layouts.app')

@section('title')
    كشوفات الاقساط
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-center">
                                <th class="text-center">#</th>
                                <th class="text-center">شهر</th>
                                <th class="text-center">المصرف</th>
                                <th class="text-center">اجمالي المدفوعات</th>
                                <th class="text-center">الاعدادات</th>
                            </thead>
                            <tr>
                                @foreach ($statements as $statement)
                                    <tr>
                                        <th class="text-center">{{$loop->iteration}}</th>
                                        <th class="text-center">{{date('Y-m', strtotime($statement->month))}}</th>
                                        <td>{{$statement->bank ? $statement->bank->name : "-"}}</td>
                                        <th class="text-center">{{$statement->total_price}} د.ل </th>
                                        <td>
                                            <a href="{{route('statements.show', $statement)}}" class="btn btn-primary text-light"><i class="fe fe-eye"></i> عرض الاقساط </a>
                                            <a href="{{route('statements.print', $statement)}}" class="btn btn-danger text-light"><i class="fe fe-print"></i> طباعة الاقساط </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
