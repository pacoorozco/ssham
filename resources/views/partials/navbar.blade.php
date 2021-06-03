<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link">@lang('site.home')</a>
        </li>
    </ul>

    <!-- start: SEARCH FORM -->
    {!! Form::open(['route' => 'search', 'class' => 'form-inline ml-3', 'method' => 'get', 'role' => 'search']) !!}
    <div class="input-group input-group-sm">
        {!! Form::text('q', null, ['class' => 'form-control form-control-navbar', 'placeholder' => __('site.search'), 'aria-label' => __('site.search')]) !!}
        <div class="input-group-append">
            {!! Form::button('<i class="fas fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-navbar')) !!}
        </div>
    </div>
    {!! Form::close() !!}
<!-- end: SEARCH FORM -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- start: USER DROPDOWN -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link" data-toggle="dropdown">
                <i class="fa fa-user"></i>
                <strong>{{ auth()->user()->username }}</strong>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- The user image in the menu -->
                <li class="dropdown-item">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-center">
                                    <i class="fa fa-user-ninja fa-3x"></i>
                                </p>
                            </div>
                            <div class="col-lg-8">
                                <p class="text-left"><strong>{{ auth()->user()->username }}</strong></p>
                                <p class="text-left small">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="dropdown-divider"></li>
                <!-- Menu Footer-->
                <li class="dropdown-footer">
                    <div class="pull-right">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-block btn-outline-danger">@lang('auth.logout')</button>
                        </form>
                    </div>
                </li>
            </ul>
        </li>
        <!-- end: USER DROPDOWN -->

    </ul>
</nav>
<!-- /.navbar -->
