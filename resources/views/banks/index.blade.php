@extends('layouts.app')

@section('title')
    عرض المصارف
@endsection

@section('content')
@foreach ($banks as $bank)
<div class="col-md-4 mb-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $bank->name }}</h5>
            <p class="card-text">الرقم: {{ $bank->number ?? '-' }}</p>
        </div>
    </div>
</div>
@endforeach
@endsection
