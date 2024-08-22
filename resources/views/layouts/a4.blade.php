<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/web-fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset('assets/css/style-a4.css') }}" media="all">
    <title>شركة فانكس | @yield('title')</title>


    @if (request()->input('landscape'))
        <style>
            @media print {
                @page {
                    size: landscape !important;
                }
            }
        </style>
    @endif

    @stack('styles')


</head>

<body onload="printDoc()" style="margin: auto">

    <page size="{{ request('size') ? request('size') : 'A4' }}" id="app"
        layout="landscape">
     
        <div class="" style="display:flex;justify-content:end;align-items:center;">
            <img src="{{ asset('/logos/logo-dark-vertical.png') }}" width="400" class="a4-header">
        </div>
      

        <div class="content" >
            @yield('content')
        </div>
    </page>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function printDoc() {
            window.print();
        }
    </script>
</body>

</html>