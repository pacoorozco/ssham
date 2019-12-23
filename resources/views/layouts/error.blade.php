<!DOCTYPE html>
<html lang="en">
<!-- start: HEAD -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Error 404') :: @lang('site.title')</title>
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
    <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}"/>
</head>
<!-- end: HEAD -->
<!-- start: BODY -->
<body class="hold-transition sidebar-mini layout-fixed">
<!-- start: PAGE -->
<div class="wrapper">
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                @yield('content')
            </div>
            <!-- /.error-page -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.0.0
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
        reserved.
    </footer>
</div>
<!-- end: PAGE -->
</body>
<!-- end: BODY -->
</html>
