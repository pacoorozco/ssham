<!DOCTYPE html>
<html lang="en">
<!-- start: HEAD -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', __('auth.login')) :: @lang('site.title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- start: META -->
    <meta content="SSH Access Manager - Administration" name="description">
    <meta content="Paco Orozco" name="author">
@yield('meta')
<!-- end: META -->
    <!-- start: GLOBAL CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- end: GLOBAL CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
@stack('styles')
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/css/adminlte.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js">
    <link rel="stylesheet" href="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js">
    <![endif]-->
    <!-- end: MAIN CSS -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
</head>
<!-- start: BODY -->
<body class="hold-transition login-page">
<!-- start: LOGIN BOX -->
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('home') }}"><b>SSHAM</b></a>
    </div>

    <!-- start: NOTIFICATIONS -->
    @include('partials.notifications')
    <!-- end: NOTIFICATIONS -->

    @yield('content')
</div>
<!-- end: LOGIN BOX -->
<!-- start: GLOBAL JAVASCRIPT -->
<script src="{{ asset('vendor/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- end: GLOBAL JAVASCRIPT -->
<!-- start: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
@stack('scripts')
<!-- end: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
<!-- start: MAIN JAVASCRIPT -->
<script src="{{ asset('vendor/AdminLTE/js/adminlte.min.js') }}"></script>
<!-- end: MAIN JAVASCRIPT -->
</body>
<!-- end: BODY -->
</html>
