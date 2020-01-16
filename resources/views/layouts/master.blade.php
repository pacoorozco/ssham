<!DOCTYPE html>
<html lang="en">
<!-- start: HEAD -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Dashboard') :: @lang('site.title')</title>
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
          href="{{ asset('//unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css') }}">
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
<!-- start: MAIN CONTAINER -->
<div class="wrapper">

    <!-- start: NAVBAR -->
    @include('partials.navbar')
    <!-- end: NAVBAR -->

    <!-- start: SIDEBAR -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="brand-link">
            <img src="{{ asset('images/SSHAM-logo-128x128.png') }}" alt="SSHAM Logo"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">SSHAM</span>
        </a>

        @include('partials.sidebar')
    </aside>
    <!-- end: SIDEBAR -->

    <!-- start: PAGE -->
    <div class="content-wrapper">
        <!-- start: PAGE HEADER -->
        <div class="content-header">
            <!-- start: PAGE TITLE & BREADCRUMB -->
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-dark">
                            @yield('header', 'Title')
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumbs')
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <!-- end: PAGE TITLE & BREADCRUMB -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- end: PAGE HEADER -->

        <!-- start: PAGE CONTENT -->
        <div class="content">
            @yield('content')
        </div>
        <!-- end: PAGE CONTENT-->
    </div>
    <!-- end: PAGE -->

    <!-- start: FOOTER -->
    @include('partials.footer')
    <!-- end: FOOTER -->

</div>
<!-- end: MAIN CONTAINER -->
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
