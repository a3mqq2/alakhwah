@extends('layouts.auth')
@section('content')
<div class="row align-items-center h-100" dir="rtl">
    <form action="{{route('login')}}" class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="POST">
        @csrf
        @method('POST')
      <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.html">
            <img src="{{asset('/logos/logo-dark-vertical.png')}}" style="width: 100%" alt="">
      </a>
      <h1 class="h6 mb-3"> تسجيل الدخول </h1>
      <div class="form-group text-left">
        <label for="inputEmail" class="sr-only">البريد الإلكتروني</label>
        <input type="email" name="email" id="inputEmail" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="البريد الإلكتروني" required="" autofocus="">
        @error('email')
        <span class="text-danger invaild-feedback text-left">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="inputPassword" class="sr-only">كلمة المرور</label>
        <input type="password" name="password" id="inputPassword" class="form-control form-control-lg" placeholder="كلمة المرور" required="">
      </div>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> تذكرني </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit"> تسجيل دخول  </button>
    </form>
  </div>
@endsection