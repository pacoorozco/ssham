
<div class="row">
    <div class="col-xs-12">

        <h2>{{ $user->username }}</h2>

        <!-- fingerprint -->
        <strong>{{ trans('user/model.fingerprint') }}</strong>
        <pre>{{ $user->fingerprint }}</pre>
        <!-- ./ fingerprint -->

        <!-- public key -->
        <strong>{{ trans('user/model.public_key') }}</strong>
        <pre>{{ $user->public_key }}</pre>
        <!-- ./ public key -->

        <!-- groups -->
        <strong>{{ trans('user/model.groups') }}</strong>
        <pre>
            @foreach($user->usergroups as $group)
                {{ $group->name }}
            @endforeach
        </pre>
        <!-- ./ groups -->

        <!-- administrator role -->
        <strong>{{ trans('user/model.is_admin') }}</strong>
        <pre>{!! ($user->hasRole('admin') ? trans('general.yes') : trans('general.no')) !!}</pre>
        <!-- ./ administrator role -->

        <!-- enabled -->
        <strong>{{ trans('user/model.enabled') }}</strong>
        <pre>{!! ($user->enabled) ? trans('general.yes') : trans('general.no') !!}</pre>
        <!-- ./ enabled -->
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('users.index') !!}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> {{ trans('general.back') }}</a>
                @if ($action == 'show')
                <a href="{!! route('users.edit', $user->id) !!}" class="btn btn-primary"><i class="fa fa-pencil"></i> {{ trans('general.edit') }}</a>
                @else
                {!! Form::button('<i class="fa fa-trash-o"></i> ' . trans('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
