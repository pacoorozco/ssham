<!DOCTYPE html>
<html>
<!-- start: HEAD -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Login') :: @lang('site.title')</title>
    <!-- start: META -->
    <meta content='width=device-width, initial-scale=1' name='viewport'>
    <meta content="@lang('site.description') - Login" name="description">
    <meta content="Paco Orozco" name="author">
    @yield('meta')
    <!-- end: META -->
    <!-- start: GLOBAL CSS -->
    {!! HTML::style('vendor/AdminLTE/bootstrap/css/bootstrap.min.css') !!}
    {!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') !!}
    {!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css') !!}
    <!-- end: GLOBAL CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @stack('styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: MAIN CSS -->
    {!! HTML::style('vendor/AdminLTE/dist/css/AdminLTE.min.css') !!}
    {!! HTML::style('css/ssham.css') !!}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js') !!}
    {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js') !!}
    <![endif]-->
    <!-- end: MAIN CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
<!-- start: BODY -->
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('home') }}"><b>@lang('site.title')</b></a>
    </div>

    <!-- start: NOTIFICATIONS -->
    @include('partials.notifications')
    <!-- end: NOTIFICATIONS -->

    <div class="login-box-body">
        @yield('content')
    </div>
</div>

<!-- start: GLOBAL JAVASCRIPT -->
{!! HTML::script('vendor/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') !!}
{!! HTML::script('vendor/AdminLTE/bootstrap/js/bootstrap.min.js') !!}
<!-- end: GLOBAL JAVASCRIPT -->
<!-- start: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
@stack('scripts')
<!-- end: JAVASCRIPT REQUIRED FOR THIS PAGE ONLY -->
<!-- start: MAIN JAVASCRIPT -->
{!! HTML::script('vendor/AdminLTE/dist/js/app.min.js') !!}
<!-- end: MAIN JAVASCRIPT -->
</body>
<!-- end: BODY -->
</html>
