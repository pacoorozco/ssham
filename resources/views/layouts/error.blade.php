<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <title>@yield('title', 'Error 404')</title>
        <!-- start: META -->
        <meta charset="utf-8" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        @yield('meta')
        <!-- end: META -->
        <!-- start: MAIN CSS -->
        {!! HTML::style(asset('plugins/bootstrap/css/bootstrap.min.css')) !!}
        {!! HTML::style(asset('plugins/font-awesome/css/font-awesome.min.css')) !!}
        {!! HTML::style(asset('fonts/style.css')) !!}
        {!! HTML::style(asset('css/main.css')) !!}
        {!! HTML::style(asset('css/main-responsive.css')) !!}
        {!! HTML::style(asset('css/theme_light.css'), array('id' => 'skin_color')) !!}
        {!! HTML::style(asset('css/print.css'), array('media' => 'print')) !!}
        <!-- end: MAIN CSS -->
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        @yield('styles')
        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
        <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}" />
    </head>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body class="error-full-page">
        <!-- start: PAGE -->
        <div class="container">
            <div class="row">
                @yield('content')
            </div>
        </div>
        <!-- end: PAGE -->
    </body>
    <!-- end: BODY -->
</html>