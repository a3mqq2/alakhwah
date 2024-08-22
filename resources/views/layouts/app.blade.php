
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <meta name="description" content="ALAKWAH SYSTEM">
    <meta name="ast" content="{{request()->cookie('ast')}}">
    <meta name="author" content="HULUL FOR TECNOLOGY">
    <link rel="icon" href="favicon.ico">
    <title>ALAKWAH SYSTEM | @yield('title')</title>
    <link rel="stylesheet" href="/assets/css/simplebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/feather.css">
    <link rel="stylesheet" href="/assets/css/daterangepicker.css">
    <link rel="stylesheet" href="/assets/css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="/assets/css/app-dark.css" id="darkTheme" disabled>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      body
      {
        font-family: 'Changa', sans-serif !important;
      }

      
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="vertical  dark rtl ">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>

        <ul class="nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="avatar avatar-sm mt-2">
                <img src="{{asset('https://ui-avatars.com/api/?name='.implode('+',explode(' ',Auth::user()->name)))}}&background=fcd106&color=fff" alt="..." class="avatar-img rounded-circle">
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right text-left" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item text-left" href="#">تسجيل خروج</a>
            </div>
          </li>
        </ul>
      </nav>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="/">
              <img src="{{asset('/logos/logo-dark-vertical.png')}}" style="width: 100%" alt="">
          </a>
          </div>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="{{route('home')}}">
                <i class="fe fe-home fe-16"></i>
                <span class="ml-3 item-text">الصفحة الرئيسية</span>
              </a>
            </li>
          </ul>

  
              <ul class="navbar-nav flex-fill w-100 mb-2">
                  {{-- <li class="nav-item dropdown">
                    <a href="#users" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                      <i class="fe fe-users fe-16"></i>
                      <span class="ml-3 item-text">المستخدمين</span>
                    </a>
                    <ul class="collapse list-unstyled pl-4 w-100" id="users">
                      <li class="nav-item">
                        <a class="nav-link pl-3" href="{{route('users.create')}}"><span class="ml-1 item-text">إضافة مستخدم جديد</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link pl-3" href="{{route('users.index')}}"><span class="ml-1 item-text">عرض المستخدمين</span></a>
                      </li>
                    </ul>
                  </li> --}}
                  

                  <li class="nav-item dropdown">
                    <a href="#contracts" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                        <i class="fe fe-file-text fe-16"></i>
                        <span class="ml-3 item-text">العقود</span>
                    </a>
                    <ul class="collapse list-unstyled pl-4 w-100" id="contracts">
                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{ route('contracts.create') }}">
                                <span class="ml-1 item-text">إضافة عقد جديد</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{ route('contracts.index') }}">
                                <span class="ml-1 item-text">عرض العقود</span>
                            </a>
                        </li>
                    </ul>
                  </li>




  


                  <li class="nav-item dropdown">
                    <a href="#payments" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                        <i class="fe fe-dollar-sign fe-16"></i>
                        <span class="ml-3 item-text">المدفوعات</span>
                    </a>
                    <ul class="collapse list-unstyled pl-4 w-100" id="payments">
                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{ route('payments.create') }}">
                                <span class="ml-1 item-text">إضافة مدفوعات جديده</span>
                            </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link pl-3" href="{{ route('payments.index') }}">
                              <span class="ml-1 item-text"> عرض المدفوعات  </span>
                          </a>
                      </li>
                    </ul>
                  </li>

                  


                  <li class="nav-item dropdown">
                    <a href="#statements" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                        <i class="fe fe-credit-card fe-16"></i>
                        <span class="ml-3 item-text">الاقساط الشهريه</span>
                    </a>
                    <ul class="collapse list-unstyled pl-4 w-100" id="statements">
                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{ route('statements.create') }}">
                                <span class="ml-1 item-text">إضافة اقساط جديده</span>
                            </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link pl-3" href="{{ route('statements.index') }}">
                              <span class="ml-1 item-text"> عرض جميع  الاقساط   </span>
                          </a>
                      </li>
                    </ul>
                  </li>




                  

                  <li class="nav-item dropdown">
                    <a href="#customers" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                      <i class="fe fe-user-check fe-16"></i>
                      <span class="ml-3 item-text">الزبائن</span>
                    </a>
                    <ul class="collapse list-unstyled pl-4 w-100" id="customers">
                      <li class="nav-item">
                        <a class="nav-link pl-3" href="{{route('customers.create')}}"><span class="ml-1 item-text">إضافة زبون جديد</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link pl-3" href="{{route('customers.index')}}"><span class="ml-1 item-text">عرض الزبائن</span></a>
                      </li>
                    </ul>
                  </li>


                  <li class="nav-item w-100">
                    <a class="nav-link" href="{{route('reports.rating')}}">
                    <i class="fe fe-activity fe-16"></i>
                      <span class="ml-3 item-text">  كشف حالات الدفع </span>
                    </a>
                  </li>


                  <li class="nav-item w-100">
                    <a class="nav-link" href="{{route('reports.index')}}">
                      <i class="fe fe-pie-chart fe-16"></i>
                      <span class="ml-3 item-text"> التقارير المالية </span>
                    </a>
                  </li>
  




                  <li class="nav-item dropdown">
                    <a href="#banks" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                        <i class="fe fe-briefcase fe-16"></i>
                        <span class="ml-3 item-text">المصارف</span>
                    </a>
                    <ul class="collapse list-unstyled pl-4 w-100" id="banks">
                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{ route('banks.create') }}">
                                <span class="ml-1 item-text">إضافة مصرف جديد</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{ route('banks.index') }}">
                                <span class="ml-1 item-text">عرض المصارف</span>
                            </a>
                        </li>
                    </ul>
                  </li>
              </ul>

         





          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="{{route('logout')}}">
                <i class="fe fe-log-out fe-16"></i>
                <span class="ml-3 item-text"> تسجيل الخروج </span>
              </a>
            </li>
          </ul>
        </nav>
      </aside>
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              @if ($errors->any())
                  @foreach ($errors->all() as $errorMessage)
                      <div class="alert  text-left alert-danger bg-danger text-white  p-2 text-left"  dir="rtl">
                          <small><i class="mx-2 fa fa-exclamation-circle mr-2 py-0"></i> {{$errorMessage}} </small>
                      </div>
                  @endforeach
              @endif
              @if (request('emessage'))
              <div class="alert text-left alert-danger bg-danger text-white  p-2 text-left"  dir="rtl">
                          <small><i class="mx-2 fa fa-exclamation-circle mr-2 py-0"></i> {{request('emessage')}} </small>
                      </div>
              @endif

              @if (session()->has('success'))
                  @foreach ( session()->get('success') as $successMessage)
                      <div class="alert  text-leftalert-success bg-success text-white  p-2 text-left"  dir="rtl">
                          <small><i class="mx-2 fa fa-check-circle mr-2 py-0"></i> {{$successMessage}} </small>
                      </div>
                  @endforeach
              @endif
              @if (session()->has('warning'))
                  @foreach ( session()->get('warning') as $warningMessage)
                      <div class="alert text-left alert-warning bg-warning text-white  p-2 text-left"  dir="rtl">
                          <small><i class="mx-2 fa fa-check-circle mr-2 py-0"></i> {{$warningMessage}} </small>
                      </div>
                  @endforeach
              @endif
            </div>
            <div class="col-12">
              <h3 class="page-title mb-3">@yield('title')</h3>
              <div id="app">
                  @yield('content')
              </div>
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/moment.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/simplebar.min.js"></script>
    <script src='/assets/js/daterangepicker.js'></script>
    <script src='/assets/js/jquery.stickOnScroll.js'></script>
    <script src="/assets/js/tinycolor-min.js"></script>
    <script src="/assets/js/config.js"></script>
    <script src="/assets/js/apps.js"></script>
    <script>
      $(document).ready(function() {
        $('select').select2();

        $("#towarehouse").change(function() {
            window.location.href = "/towarehouse?id=" + $(this).val();
        });
      });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
  </body>
</html>