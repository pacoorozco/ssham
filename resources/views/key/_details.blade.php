<div class="card">
    <div class="card-header">
        <h2>{{ $key->name }} @if(!$key->enabled)<span class="badge badge-secondary">{{ __('general.disabled') }}</span>@endif </h2>
    </div>
    <div class="card-body">

        <!-- fingerprint -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.fingerprint')</strong>
            </div>
            <div class="col-10">
                {{ $key->fingerprint }}
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
                <strong>@lang('key/model.public_key')</strong>
            </div>
            <div class="col-10">
                <pre class="key-code">{{ $key->public_key }}</pre>
            </div>
        </div>
        <!-- ./ public key -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.groups')</strong>
            </div>
            <div class="col-10">
                @forelse($key->keygroups as $group)
                    <span class="badge badge-primary">{{ $group->name }}</span>
                @empty
                    @lang('key/model.no_groups')
                @endforelse
            </div>
        </div>
        <!-- ./ groups -->

        <!-- enabled -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.enabled')</strong>
            </div>
            <div class="col-10">
                {{ ($key->enabled) ? __('general.yes') : __('general.no') }}
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

