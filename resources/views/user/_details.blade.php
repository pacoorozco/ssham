
<div class="row">
    <div class="col-xs-12">

        <dl class="dl-horizontal">

        <!-- username -->
        <dt>{{ Lang::get('admin/user/model.username') }}</dt>
        <dd>{{{ $user->username }}}</dd>
        <!-- ./ username -->

        <!-- fullname -->
        <dt>{{ Lang::get('admin/user/model.fullname') }}</dt>
        <dd>{{{ $user->fullname }}}</dd>
        <!-- ./ fullname -->

        <!-- email -->
        <dt>{{ Lang::get('admin/user/model.email') }}</dt>
        <dd>{{{ $user->email }}}</dd>
        <!-- ./ email -->

        <!-- activation status -->
        <dt>{{ Lang::get('admin/user/model.confirm') }}</dt>
        <dd>{{{ ($user->confirmed) ? Lang::get('general.yes') : Lang::get('general.no') }}}</dd>
        <!-- ./ activation status -->

        <!-- roles -->
        <dt>{{ Lang::get('admin/user/model.roles') }}</dt>
        <dd>
            <ul class="list-unstyled">
        @foreach ($roles as $role)
        {{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? '<li>' . $role->name .'</li>' : '') }}
        @endforeach
            </ul>
        </dd>
        <!-- ./ roles -->

        </dl>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">{{ Lang::get('button.back') }}</a>
                @if ($action == 'show')
                <a href="{{ URL::route('admin.users.edit', $user->id) }}" class="btn btn-primary">{{ Lang::get('button.edit') }}</a>
                @else
                {{ Form::button(Lang::get('button.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) }}
                @endif
            </div>
        </div>

    </div>
</div>
