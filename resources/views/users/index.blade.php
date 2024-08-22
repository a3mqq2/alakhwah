@extends('layouts.app')
@section('title')
    عرض المستخدمين
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary">
                        <th>#</th>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>البريد الالكتروني</th>
                        <th>الفروع</th>
                        <th>الحاله</th>
                        <th>الاعدادات</th>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->phone ?? '-'}}</td>
                                <td>{{$user->email}}</td> 
                                <td>
                                    @foreach ($user->warehouses as $warehouse)
                                        <div class="badge badge-secondary">{{$warehouse->name}}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($user->active)
                                        <span class="badge badge-success">مفعل</span>
                                        @else 
                                        <span class="badge badge-danger">محظور</span>
                                    @endif
                                </td>
                                <td>
                                    
                                    @if ($user->active)
                                    <a href="{{route('users.active', $user)}}" class="btn btn-warning btn-sm"><i class="fe fe-power"></i>حظر </a>
                                        @else 
                                    <a href="{{route('users.active', $user)}}" class="btn btn-success btn-sm"><i class="fe fe-power"></i> تفعيل </a>
                                    @endif
                                    <a href="{{route('users.edit', $user)}}" class="btn btn-info btn-sm"><i class="fe fe-edit"></i> تعديل </a>
                                    @if ($user->id != 1)

                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_user_{{$user->id}}">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$users->appends($_GET)->links()}}
            </div> 
            @foreach ($users as $user)
                <div class="modal fade" id="delete_user_{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="delete_user_{{$user->id}}Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="delete_user_{{$user->id}}Label">هل انت متأكد من الحذف ؟ </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                           <form action="{{route('users.destroy', $user)}}" method="POST">
                            @csrf
                            @method('DELETE')
                                <h5 class="text-center font-weight-bold mb-2">هل انت متأكد من الحذف ؟ </h5>
                                <button  class="btn btn-danger btn-block"> حذف </button>
                                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">إلغاء</button>
                           </form>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection