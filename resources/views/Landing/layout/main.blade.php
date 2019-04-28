<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta-details')
    <link rel="stylesheet" href="{{ URL::asset('public/css/bootstrap/bootstrap.min.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('public/css/pageloader.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('public/css/profile.css') }} ">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{URL::asset('public/css/assets/mainfavicon.png')}}" type="image/png">
    <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5c7d3f0c9fbe5a0017077923&product=inline-share-buttons' async='async'></script>
    <title>Home</title>
    <style>
        body{
            font-family: 'Noto Serif', serif;
        }
    </style>
    @yield('pageCss')
</head>
<body onload="myFunction()">
    @yield('nav-bar')
    <div id="loader"></div>
    <div style="display:none;" id="myDiv" class="animate-bottom">
        <div class="content-container mt-5">
            @yield('content')
        </div>
    </div>
</body>
<script src="{{ URL::asset('public/js/bootstrap/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap/popper.min.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/js/holder.min.js') }}"></script>
<script src="{{ URL::asset('public/js/sweetalert.js') }}"></script>
<script src="{{ URL::asset('public/js/jquery-validator.js') }}"></script>

<script>
    var myVar;
    function myFunction() {
        myVar = setTimeout(showPage, 100);
    }
    function showPage() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("myDiv").style.display = "block";
    }
</script>
@yield('pageJs')
</html>