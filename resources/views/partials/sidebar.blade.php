<div class="sidebar">
    <!-- start: MAIN NAVIGATION MENU -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('home*') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="nav-icon fa fa-home"></i>
                    <p>@lang('site.home')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="nav-icon fa fa-user"></i>
                    <p>@lang('site.users')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('keygroups*') ? 'active' : '' }}" href="{{ route('keygroups.index') }}">
                    <i class="nav-icon fa fa-users"></i>
                    <p>{{ __('site.user_groups') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('hosts*') ? 'active' : '' }}" href="{{ route('hosts.index') }}">
                    <i class="nav-icon fa fa-laptop"></i>
                    <p>@lang('site.hosts')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('hostgroups*') ? 'active' : '' }}" href="{{ route('hostgroups.index') }}">
                    <i class="nav-icon fa fa-tasks"></i>
                    <p>{{ __('site.host_groups') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('rules*') ? 'active' : '' }}" href="{{ route('rules.index') }}">
                    <i class="nav-icon fa fa-database"></i>
                    <p>@lang('site.rules')</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="nav-icon fa fa-clipboard"></i>
                    <p>@lang('site.settings')</p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- end: MAIN NAVIGATION MENU -->
</div>

