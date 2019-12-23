<div class="sidebar">
    <!-- start: MAIN NAVIGATION MENU -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a class="nav-link @activeIfInRouteGroup(home)" href="{{ route('home') }}">
                    <i class="nav-icon fa fa-home"></i>
                    <p>@lang('site.home')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @activeIfInRouteGroup(users)" href="{{ route('users.index') }}">
                    <i class="nav-icon fa fa-user"></i>
                    <p>@lang('site.users')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @activeIfInRouteGroup(usergroups)" href="{{ route('usergroups.index') }}">
                    <i class="nav-icon fa fa-users"></i>
                    <p>{{ __('site.user_groups') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @activeIfInRouteGroup(hosts)" href="{{ route('hosts.index') }}">
                    <i class="nav-icon fa fa-laptop"></i>
                    <p>@lang('site.hosts')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @activeIfInRouteGroup(hostgroups)" href="{{ route('hostgroups.index') }}">
                    <i class="nav-icon fa fa-tasks"></i>
                    <p>{{ __('site.host_groups') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @activeIfInRouteGroup(rules)" href="{{ route('rules.index') }}">
                    <i class="nav-icon fa fa-database"></i>
                    <p>@lang('site.rules')</p>
                </a>
            </li>
            {{-- <li {!! Request::is('settings*') ? ' class="active"' : '' !!}>
                <a href="{!! route('settings.index') !!}"><i class="clip-settings"></i>
                    <span class="title"> @lang('site.settings') </span>
                    {!! Request::is('settings*') ? '<span class="selected"></span>' : '' !!}
                </a>
            </li>
            --}}
        </ul>
    </nav>
    <!-- end: MAIN NAVIGATION MENU -->
</div>

