<div class="card">
    <div class="card-header bg-cyan">
       <h2 class="card-title">{{ $key->username }} @if(!$key->enabled)<span class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>@endif </h2>
    </div>
    <div class="card-body">

        <!-- username -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.username')</strong>
            </div>
            <div class="col-10">
                {{ $key->username }}
            </div>
        </div>
        <!-- ./ username -->

        <!-- fingerprint -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.fingerprint')</strong>
            </div>
            <div class="col-10">
                {{ $key->fingerprint }}
            </div>
        </div>
        <!-- ./ fingerprint -->

        <!-- public key -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.public_key')</strong>
            </div>
            <div class="col-10">
                <pre class="key-code">{{ $key->public }}</pre>
            </div>
        </div>
        <!-- ./ public key -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.groups')</strong>
            </div>
            <div class="col-10">
                <ul class="list-inline">
                @forelse($key->groups as $group)
                        <li class="list-inline-item"><a href="{{ route('keygroups.show', $group->id) }}">{{ $group->name }}</a></li>
                @empty
                        <li class="list-inline-item">@lang('key/model.no_groups')</li>
                @endforelse
                </ul>
            </div>
        </div>
        <!-- ./ groups -->

        <!-- enabled -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.enabled')</strong>
            </div>
            <div class="col-10">
                @if ($key->enabled)
                    <span class="badge badge-pill badge-success">{{ __('general.enabled') }}</span>
                @else
                    <span class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>
                @endif
            </div>
        </div>
        <!-- ./ enabled -->

    </div>
    <div class="card-footer">
        <a href="{{ route('keys.index') }}" class="btn btn-primary" role="button">
            <i class="fa fa-arrow-left"></i> @lang('general.back')
        </a>
        @if ($action == 'show')
            <a href="{{ route('keys.edit', $key->id) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pen"></i> @lang('general.edit')
            </a>
        @else
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> @lang('general.delete')</button>
        @endif
    </div>
</div>

