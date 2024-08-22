@extends('layouts.app')
@section('title')
    إنشاء مستخدم جديد
@endsection
@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <form action="{{route('users.store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-4">
                        <label for="">الاسم بالكامل</label>
                        <input type="text" name="name" value="{{old('name')}}" required id="" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for=""> رقم الهاتف </label>
                        <input type="text" name="phone" value="{{old('phone')}}" required id="" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for=""> البريد الالكتروني  </label>
                        <input type="email" required value="{{old('email')}}" name="email" id="" class="form-control">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="">كلمة المرور</label>
                        <input type="password" name="password" required id="" class="form-control">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="">اعاده تأكيد كلمه المرور</label>
                    <input type="password" name="password_confirmation" required id="" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <th class="bg-primary text-center">الصلاحيه</th>
                                <th class="bg-primary text-center">التفعيل</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2" class="bg-light text-center text-primary">الاداره العامه</th>
                                </tr>
                                <tr>
                                    <th class="text-center">المستخدمين</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="users" value="{{old('users',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">المخازن</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="mng_warehouses" value="{{old('mng_warehouses',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">الزبونين</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="suppliers" value="{{old('suppliers',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">الزبائن</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="customers" value="{{old('customers',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="bg-light text-center text-primary"> اداره العمليات </th>
                                </tr>
                                <tr>
                                    <th class="text-center">الاصناف</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="products" value="{{old('products',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">المشتريات</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="purchases" value="{{old('purchases',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">مؤشرات الخطر</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="rates" value="{{old('rates',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="bg-light text-center text-primary"> اداره المخازن </th>
                                </tr>
                                <tr>
                                    <th class="text-center">استقبال الشحنات</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="recive_purchases" value="{{old('recive_purchases',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">اصناف المخازن</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="inventory" value="{{old('inventory',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center"> المبيعات </th>
                                    <td class="text-center">
                                        <input type="checkbox" name="sales" value="{{old('sales',1)}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center"> التقارير </th>
                                    <td class="text-center">
                                        <input type="checkbox" name="reports" value="{{old('reports',1)}}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="mt-3">
                            <button class="btn btn-primary"> إنشاء </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection