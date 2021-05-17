<div class="card">
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.edit', $user) }}">
                    Personal information
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('users.tokens.index', $user) }}">
                    @lang('user/personal_access_token.title')
                </a>
            </li>
        </ul>
    </div>
</div>
