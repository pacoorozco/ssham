<div class="card">
    <div class="card-header">
        <h2>{{ $user->username }} @if(!$user->enabled)<span class="badge badge-secondary">{{ __('general.disabled') }}</span>@endif </h2>
    </div>
    <div class="card-body">

        <!-- email -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('user/model.email')</strong>
            </div>
            <div class="col-10">
                {{ $user->email }}
            </div>
        </div>
        <!-- ./email -->

        <!-- fingerprint -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('user/model.fingerprint')</strong>
            </div>
            <div class="col-10">
                {{ $user->fingerprint }}
                <a data-toggle="collapse" href="#collapsePublicKey" aria-expanded="false"
                   aria-controls="collapsePublicKey">
                    <i class="fa fa-caret-down"></i>
                </a>
            </div>
        </div>
        <!-- ./ fingerprint -->

        <!-- public key -->
        <div class="row collapse" id="collapsePublicKey">
            <div class="col-2">
                <strong>@lang('user/model.public_key')</strong>
            </div>
            <div class="col-10">
                <pre class="key-code">{{ $user->public_key }}</pre>
            </div>
        </div>
        <!-- ./ public key -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('user/model.groups')</strong>
            </div>
            <div class="col-10">
                @forelse($user->usergroups as $group)
                    <span class="badge badge-primary">{{ $group->name }}</span>
                @empty
                    @lang('user/model.no_groups')
                @endforelse
            </div>
        </div>
        <!-- ./ groups -->

        <!-- administrator role -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('user/model.is_admin')</strong>
            </div>
            <div class="col-10">
                {{ ($user->hasRole('admin') ? __('general.yes') : __('general.no')) }}
            </div>
        </div>
        <!-- ./ administrator role -->

        <!-- enabled -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('user/model.enabled')</strong>
            </div>
            <div class="col-10">
                {{ ($user->enabled) ? __('general.yes') : __('general.no') }}
            </div>
        </div>
        <!-- ./ enabled -->

    </div>
    <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-primary" role="button">
            <i class="fa fa-arrow-left"></i> @lang('general.back')
        </a>
        @if ($action == 'show')
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pen"></i> @lang('general.edit')
            </a>
        @else
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> @lang('general.delete')</button>
        @endif
    </div>
</div>

