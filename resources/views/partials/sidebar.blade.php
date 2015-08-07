<div class="main-navigation navbar-collapse collapse">
    <!-- start: MAIN MENU TOGGLER BUTTON -->
    <div class="navigation-toggler">
        <i class="clip-chevron-left"></i>
        <i class="clip-chevron-right"></i>
    </div>
    <!-- end: MAIN MENU TOGGLER BUTTON -->
    <!-- start: MAIN NAVIGATION MENU -->
    <ul class="main-navigation-menu">
        <li {!! Request::is('home') ? ' class="active"' : '' !!}>
            <a href="{!! route('home') !!}"><i class="clip-home-3"></i>
                <span class="title"> {{ trans('site.home') }} </span>
                {!! Request::is('home') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li {!! Request::is('users*') ? ' class="active"' : '' !!}>
            <a href="{!! route('users.index') !!}"><i class="clip-user"></i>
                <span class="title"> {{ trans('site.users') }} </span>
                {!! Request::is('users*') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li {!! Request::is('usergroups*') ? ' class="active"' : '' !!}>
            <a href="{!! route('usergroups.index') !!}"><i class="clip-users"></i>
                <span class="title"> {{ trans('site.user_groups') }} </span>
                {!! Request::is('usergroups*') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li {!! Request::is('hosts*') ? ' class="active"' : '' !!}>
            <a href="{!! route('hosts.index') !!}"><i class="clip-screen"></i>
                <span class="title"> {{ trans('site.hosts') }} </span>
                {!! Request::is('hosts*') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li {!! Request::is('hostgroups*') ? ' class="active"' : '' !!}>
            <a href="{!! route('hostgroups.index') !!}"><i class="fa fa-tasks"></i>
                <span class="title"> {{ trans('site.host_groups') }} </span>
                {!! Request::is('hostgroups*') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li {!! Request::is('rules*') ? ' class="active"' : '' !!}>
            <a href="{!! route('rules.index') !!}"><i class="clip-database"></i>
                <span class="title"> {{ trans('site.rules') }} </span>
                {!! Request::is('rules*') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li {!! Request::is('settings*') ? ' class="active"' : '' !!}>
            <a href="{!! route('settings.index') !!}"><i class="clip-settings"></i>
                <span class="title"> {{ trans('site.settings') }} </span>
                {!! Request::is('settings*') ? '<span class="selected"></span>' : '' !!}
            </a>
        </li>
        <li></li>
    </ul>
    <!-- end: MAIN NAVIGATION MENU -->
</div>
