<div class="card">
    <div class="card-header bg-cyan">
        <h2 class="card-title">{{ $user->username }} @if(!$user->enabled)<span class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>@endif </h2>
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

        <!-- enabled -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('user/model.enabled')</strong>
            </div>
            <div class="col-10">
                @if ($user->enabled)
                    <span class="badge badge-pill badge-success">{{ __('general.enabled') }}</span>
                @else
                    <span class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>
                @endif
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

