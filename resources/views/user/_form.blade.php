{{-- Create / Edit User Form --}}

@if (isset($user))
{{ Form::model($user, array(
            'route' => array('admin.users.update', $user->id),
            'method' => 'put'
            )) }}
@else
{{ Form::open(array(
            'route' => array('admin.users.store'),
            'method' => 'post'
            )) }}
@endif

<div class="row">
    <div class="col-xs-6">

        <!-- username -->
        <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
            {{ Form::label('username', Lang::get('admin/user/model.username'), array('class' => 'control-label')) }}
            <div class="controls">
                {{-- TODO: If $user->username == 'admin' this input text must be disabled --}}
                @if ($action == 'create')
                {{ Form::text('username', null, array('class' => 'form-control')) }}
                @else
                {{ Form::text('username', null,
                    ($user->username == 'admin') ? array('disabled' => 'disabled', 'class' => 'form-control') : array('class' => 'form-control')
                    ) }}
                @endif
                <span class="help-block">{{{ $errors->first('username', ':message') }}}</span>
            </div>
        </div>
        <!-- ./ username -->

        <!-- fullname -->
        <div class="form-group {{{ $errors->has('fullname') ? 'has-error' : '' }}}">
            {{ Form::label('fullname', Lang::get('admin/user/model.fullname'), array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::text('fullname', null, array('class' => 'form-control')) }}
                <span class="help-block">{{{ $errors->first('fullname', ':message') }}}</span>
            </div>
        </div>
        <!-- ./ fullname -->

        <!-- Email -->
        <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
            {{ Form::label('email', Lang::get('admin/user/model.email'), array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::email('email', null, array('class' => 'form-control')) }}
                <span class="help-block">{{{ $errors->first('email', ':message') }}}</span>
            </div>
        </div>
        <!-- ./ email -->

        <!-- Password -->
        <div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
            {{ Form::label('password', Lang::get('admin/user/model.password'), array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password', array('class' => 'form-control')) }}
                <span class="help-block">{{{ $errors->first('password', ':message') }}}</span>
            </div>
        </div>
        <!-- ./ password -->

        <!-- Password Confirm -->
        <div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
            {{ Form::label('password_confirmation', Lang::get('admin/user/model.password_confirmation'), array('class' => 'control-label')) }}
            <div class="controls">
                {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                <span class="help-block">{{{ $errors->first('password_confirmation', ':message') }}}</span>
            </div>
        </div>
        <!-- ./ password confirm -->

    </div>
    <div class="col-xs-6">

        <!-- Activation Status -->
        <div class="form-group {{{ $errors->has('activated') || $errors->has('confirm') ? 'has-error' : '' }}}">
            {{ Form::label('confirm', Lang::get('admin/user/model.confirm'), array('class' => 'control-label')) }}
            <div class="controls">
                @if ($action == 'create')
                {{ Form::select('confirm', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), null, array('class' => 'form-control')) }}
                @else
                {{ Form::select('confirm', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), null,
                    ($user->id === Confide::user()->id ? array('disabled' => 'disabled', 'class' => 'form-control') : array('class' => 'form-control'))
                    ) }}
                @endif
                <span class="help-block">{{{ $errors->first('confirm', ':message') }}}</span>
            </div>
        </div>
        <!-- ./ activation status -->

        <!-- roles -->
        <div class="form-group {{{ $errors->has('roles') ? 'has-error' : '' }}}">
            {{ Form::label('roles', Lang::get('admin/user/model.roles'), array('class' => 'control-label')) }}
            <div class="controls">
                <select class="form-control" name="roles[]" id="roles[]" multiple>
                    @foreach ($roles as $role)
                    @if ($action == 'create')
                    <option value="{{{ $role->id }}}"{{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
                    @else
                    <option value="{{{ $role->id }}}"{{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
                    @endif
                    @endforeach
                </select>

                <span class="help-block">
                    {{{ Lang::get('admin/user/messages.roles_help') }}}
                </span>
            </div>
        </div>
        <!-- ./ roles -->

    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                {{ Form::button(Lang::get('button.save'), array('type' => 'submit', 'class' => 'btn btn-success')) }}
            </div>
        </div>
        <!-- ./ form actions -->

    </div>
</div>

{{ Form::close() }}
