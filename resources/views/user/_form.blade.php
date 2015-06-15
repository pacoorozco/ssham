{{-- Create / Edit User Form --}}

@if (isset($user))
{!! Form::model($user, array(
            'route' => array('users.update', $user->id),
            'method' => 'put'
            )) !!}
@else
{!! Form::open(array(
            'route' => array('users.store'),
            'method' => 'post'
            )) !!}
@endif

<div class="row">
    <div class="col-xs-6">

        <!-- username -->
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('name', Lang::get('user/model.name'), array('class' => 'control-label')) !!}
            <div class="controls">
                {{-- TODO: If $user->username == 'admin' this input text must be disabled --}}
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <!-- ./ username -->
        
        <!-- auth type -->
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('type', Lang::get('user/model.type'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('type', array('local' => 'Local', 'external' => 'External'), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>        
        <!-- ./ auth type -->

        <!-- password -->
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password', Lang::get('user/model.password'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <!-- ./ password -->

        <!-- password confirm -->
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            {!! Form::label('password_confirmation', Lang::get('user/model.password_confirmation'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
            </div>
        </div>
        <!-- ./ password confirm -->

    </div>
    <div class="col-xs-6">

        <!-- Activation Status -->
        <div class="form-group {{ $errors->has('activated') || $errors->has('confirm') ? 'has-error' : '' }}">
            {!! Form::label('confirm', Lang::get('user/model.confirm'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('confirm', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('confirm', ':message') }}</span>
            </div>
        </div>
        <!-- ./ activation status -->
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                {!! Form::button(Lang::get('button.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            </div>
        </div>
        <!-- ./ form actions -->

    </div>
</div>

{!! Form::close() !!}
