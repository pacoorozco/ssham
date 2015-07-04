
<div class="row">
    <div class="col-xs-12">

        <dl class="dl-horizontal">

        <!-- username -->
        <dt>{!! Lang::get('user/model.username') !!}</dt>
        <dd>{{ $user->username }}</dd>
        <!-- ./ username -->

        <!-- fingerprint -->
        <dt>{!! Lang::get('user/model.fingerprint') !!}</dt>
        <dd>{{ $user->fingerprint }}</dd>
        <!-- ./ fingerprint -->

        <!-- public key -->
        <dt>{!! Lang::get('user/model.publickey') !!}</dt>
        <dd>{{ $user->public_key }}</dd>
        <!-- ./ public key -->

        <!-- activation status -->
        <dt>{!! Lang::get('user/model.confirm') !!}</dt>
        <dd>{{ ($user->enabled) ? Lang::get('general.yes') : Lang::get('general.no') }}</dd>
        <!-- ./ activation status -->

        </dl>
        
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
