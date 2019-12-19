<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link">{{ __('site.home') }}</a>
        </li>
    </ul>

    <!-- start: SEARCH FORM -->

    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- end: SEARCH FORM -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- start: USER DROPDOWN -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link" data-toggle="dropdown">
                <i class="fa fa-user"></i>
                <strong>{{ auth()->user()->name }}</strong>
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
                                <p class="text-left"><strong>{{ auth()->user()->name }}</strong></p>
                                <p class="text-left small">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="dropdown-divider"></li>
                <!-- Menu Footer-->
                <li class="dropdown-footer">
                    <div class="pull-right">
                        {!! Form::open(['url' => '/logout']) !!}
                        {!! Form::button(__('auth.logout'), ['type' => 'submit', 'class' => 'btn btn-block btn-outline-danger']) !!}
                        {!! Form::close() !!}
                    </div>
                </li>
            </ul>
        </li>
        <!-- end: USER DROPDOWN -->

    </ul>
</nav>
<!-- /.navbar -->
