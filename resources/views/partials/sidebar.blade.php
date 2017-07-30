<!-- start: MAIN NAVIGATION MENU -->
<ul class="sidebar-menu">
    <li class="header">{{ trans('site.navigation') }}</li>

    <li {!! (Request::is('home') ? ' class="active"' : '') !!}>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i><span>{{ trans('site.dashboard') }}</span>
        </a>
    </li>

    <li {!! (Request::is('users*') ? ' class="active"' : '') !!}>
        <a href="{{ route('users.index') }}">
            <i class="fa fa-user"></i><span>{{ trans('site.users') }}</span>
        </a>
    </li>

    <li {!! Request::is('usergroups*') ? ' class="active"' : '' !!}>
        <a href="{{ route('usergroups.index') }}">
            <i class="fa fa-users"></i><span>{{ trans('site.user_groups') }}</span>
        </a>
    </li>
    <li {!! Request::is('hosts*') ? ' class="active"' : '' !!}>
        <a href="{{ route('hosts.index') }}">
            <i class="fa fa-server"></i><span>{{ trans('site.hosts') }}</span>
        </a>
    </li>
    <li {!! Request::is('hostgroups*') ? ' class="active"' : '' !!}>
        <a href="{{ route('hostgroups.index') }}">
            <i class="fa fa-tasks"></i><span>{{ trans('site.host_groups') }}</span>
        </a>
    </li>
    <li {!! Request::is('rules*') ? ' class="active"' : '' !!}>
        <a href="{{ route('rules.index') }}">
            <i class="fa fa-database"></i><span>{{ trans('site.rules') }}</span>
        </a>
    </li>
    <li {!! Request::is('settings*') ? ' class="active"' : '' !!}>
        <a href="{{ route('settings.index') }}">
            <i class="fa fa-gears"></i><span>{{ trans('site.settings') }}</span>
        </a>
    </li>
</ul>
<!-- end: MAIN NAVIGATION MENU -->