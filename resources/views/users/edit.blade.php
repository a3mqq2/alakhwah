@extends('layouts.app')
@section('title')
    تعديل بيانات المستخدم {{ $user->name }}
@endsection
@section('content')
    <div class="card mt-2">
        <div class="card-body">
            <form action="{{route('users.update', $user)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <label for="">الاسم بالكامل</label>
                        <input type="text" name="name" value="{{old('name', $user->name)}}" required id="" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for=""> رقم الهاتف </label>
                        <input type="text" name="phone" value="{{old('phone', $user->phone)}}" required id="" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for=""> البريد الالكتروني  </label>
                        <input type="email" required value="{{old('email', $user->email)}}" name="email" id="" class="form-control">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="">كلمة المرور</label>
                        <input type="password" name="password"  id="" class="form-control">
                        <span class="text-muted">لا تعدلها الا اذا اردت ذلك</span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="">اعاده تأكيد كلمه المرور</label>
                        <input type="password" name="password_confirmation"  id="" class="form-control">
                        <span class="text-muted">لا تعدلها الا اذا اردت ذلك</span>
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
                                        <input type="checkbox" name="users" {{$user->users ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">المخازن</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="mng_warehouses" {{$user->mng_warehouses ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">الزبونين</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="suppliers" {{$user->suppliers ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">الزبائن</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="customers" {{$user->customers ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="bg-light text-center text-primary"> اداره العمليات </th>
                                </tr>
                                <tr>
                                    <th class="text-center">الاصناف</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="products" {{$user->products ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">المشتريات</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="purchases" {{$user->purchases ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">مؤشرات الخطر</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="rates" {{$user->rates ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="bg-light text-center text-primary"> اداره المخازن </th>
                                </tr>
                                <tr>
                                    <th class="text-center">استقبال الشحنات</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="recive_purchases" {{$user->recive_purchases ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">اصناف المخازن</th>
                                    <td class="text-center">
                                        <input type="checkbox" name="inventory" {{$user->inventory ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center"> المبيعات </th>
                                    <td class="text-center">
                                        <input type="checkbox" name="sales" {{$user->sales ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center"> التقارير </th>
                                    <td class="text-center">
                                        <input type="checkbox" name="reports" {{$user->reports ? "checked" : ""}} value="1">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button class="btn btn-primary text-light">حفظ</button>
            </form>
        </div>
    </div>
@endsection