<div class="sidebar">
    <!-- start: MAIN NAVIGATION MENU -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('home*') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="nav-icon fa fa-tachometer-alt"></i>
                    <p>{{ __('site.home') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('search*') ? 'active' : '' }}" href="{{ route('search') }}">
                    <i class="nav-icon fa fa-search"></i>
                    <p>{{ __('site.search') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('keys*') ? 'active' : '' }}" href="{{ route('keys.index') }}">
                    <i class="nav-icon fa fa-key"></i>
                    <p>{{ __('site.keys') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('keygroups*') ? 'active' : '' }}" href="{{ route('keygroups.index') }}">
                    <i class="nav-icon fa fa-briefcase"></i>
                    <p>{{ __('site.key_groups') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('hosts*') ? 'active' : '' }}" href="{{ route('hosts.index') }}">
                    <i class="nav-icon fa fa-laptop"></i>
                    <p>{{ __('site.hosts') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('hostgroups*') ? 'active' : '' }}" href="{{ route('hostgroups.index') }}">
                    <i class="nav-icon fa fa-server"></i>
                    <p>{{ __('site.host_groups') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('rules*') ? 'active' : '' }}" href="{{ route('rules.index') }}">
                    <i class="nav-icon fa fa-dungeon"></i>
                    <p>{{ __('site.rules') }}</p>
                </a>
            </li>

            <li class="nav-header">{{ __('site.admin_sidebar_header') }}</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="nav-icon fa fa-users"></i>
                    <p>{{ __('site.users') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('audit*') ? 'active' : '' }}" href="{{ route('audit') }}">
                    <i class="nav-icon fa fa-binoculars"></i>
                    <p>{{ __('site.audit') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="nav-icon fa fa-cog"></i>
                    <p>{{ __('site.settings') }}</p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- end: MAIN NAVIGATION MENU -->
</div>

