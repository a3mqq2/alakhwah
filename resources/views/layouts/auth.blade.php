<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="ALAKWAH SYSTEM">
        <meta name="author" content="HULUL FOR TECNOLOGY">
        <link rel="icon" href="favicon.ico">
        <title>ALAKWAH SYSTEM | @yield('title')</title>
        <!-- Simple bar CSS -->
        <link rel="stylesheet" href="{{asset('/assets/css/simplebar.css')}}">
        <!-- Fonts CSS -->
        <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <!-- Icons CSS -->
        <link rel="stylesheet" href="{{asset('/assets/css/feather.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/css/select2.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/css/dropzone.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/css/uppy.min.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/css/jquery.steps.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/css/jquery.timepicker.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/css/quill.snow.css')}}">
        <!-- Date Range Picker CSS -->
        <link rel="stylesheet" href="{{asset('/assets/css/daterangepicker.css')}}">
        <!-- App CSS -->
        <link rel="stylesheet" href="{{asset('/assets/css/app-light.css')}}" id="lightTheme">
        <link rel="stylesheet" href="{{asset('/assets/css/app-dark.css')}}" id="darkTheme" disabled>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Changa:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
          * {
            font-family: 'Changa', sans-serif !important;
          }
        </style>
      </head>
<body>
    <div class="wrapper vh-100">
        @yield('content')
      </div>
    {{-- scripts --}}
    <script src="{{asset('/assets/js/popper.min.js')}}"></script>
    <script src="{{asset("/assets/js/moment.min.js")}}"></script>
    <script src="{{asset('/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/assets/js/simplebar.min.js')}}"></script>
    <script src='{{asset('/assets/js/daterangepicker.js')}}'></script>
    <script src='{{asset('/assets/js/jquery.stickOnScroll.js')}}'></script>
    <script src="{{asset('/assets/js/tinycolor-min.js')}}"></script>
    <script src="{{asset('/assets/js/config.js')}}"></script>
    <script src="{{asset('/assets/js/d3.min.js')}}"></script>
    <script src="{{asset('/assets/js/topojson.min.js')}}"></script>
    <script src="{{asset('/assets/js/datamaps.all.min.js')}}"></script>
    <script src="{{asset('/assets/js/datamaps-zoomto.js')}}"></script>
    <script src="{{asset('/assets/js/datamaps.custom.js')}}"></script>
    <script src="{{asset('/assets/js/Chart.min.js')}}"></script>
</body>
</html>