
<div class="row">
    <div class="col-xs-12">

        <h2>{{ $user->username }}</h2>

        <!-- fingerprint -->
        <strong>{!! Lang::get('user/model.fingerprint') !!}</strong>
        <pre>{{ $user->fingerprint }}</pre>
        <!-- ./ fingerprint -->

        <!-- public key -->
        <strong>{!! Lang::get('user/model.public_key') !!}</strong>
        <pre>{{ $user->public_key }}</pre>
        <!-- ./ public key -->

        <!-- groups -->
        <strong>{!! Lang::get('user/model.groups') !!}</strong>
        <pre>
            @foreach($user->groups as $group)
                {{ $group->name }}
            @endforeach
        </pre>
        <!-- ./ groups -->

        <!-- administrator role -->
        <strong>{!! Lang::get('user/model.is_admin') !!}</strong>
        <pre>{!! ($user->hasRole('admin') ? Lang::get('general.yes') : Lang::get('general.no')) !!}</pre>
        <!-- ./ administrator role -->

        <!-- enabled -->
        <strong>{!! Lang::get('user/model.enabled') !!}</strong>
        <pre>{!! ($user->enabled) ? Lang::get('general.yes') : Lang::get('general.no') !!}</pre>
        <!-- ./ enabled -->
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('users.index') !!}" class="btn btn-primary">{!! Lang::get('button.back') !!}</a>
                @if ($action == 'show')
                <a href="{!! route('users.edit', $user->id) !!}" class="btn btn-primary">{!! Lang::get('button.edit') !!}</a>
                @else
                {!! Form::button(Lang::get('button.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
