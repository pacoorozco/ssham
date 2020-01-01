<!DOCTYPE html>
<html lang="en">
<!-- start: HEAD -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Error 404') :: @lang('site.title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<!-- end: HEAD -->
<!-- start: BODY -->
<body class="hold-transition lockscreen">
<!-- automatic element centering -->
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="{{ route('home') }}"><b>SSHAM</b></a>
    </div>
    <!-- error-page -->
    <div class="help-block text-center">
        @yield('content')
    </div>
    <!-- ./ error-page -->
</div>
<!-- ./ automatic element centering -->

<footer class="lockscreen-footer text-center mt-5">
    Powered by <a href="https://github.com/pacoorozco/ssham" rel="nofollow">SSH Access Manager</a>
    v{{ Config::get('app.version') }}<br>
    <strong>Copyright &copy; 2013-{{ date('Y') }} <a href="http://pacoorozco.info" rel="nofollow">Paco
            Orozco</a></strong>
</footer>

</body>
<!-- end: BODY -->
</html>
