{{-- Edit Settings Form --}}

<div class="row">
    <div class="col-xs-12">

        <!-- authorized_keys -->
        <div class="form-group {{ $errors->has('authorized_keys') ? 'has-error' : '' }}">
            {!! Form::label('authorized_keys', Lang::get('settings/model.authorized_keys'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('authorized_keys', Registry::get('authorized_keys'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('authorized_keys', ':message') }}</span>
            </div>
        </div>
        <!-- ./ authorized_keys -->

        <!-- private_key -->
        <div class="form-group {{ $errors->has('private_key') ? 'has-error' : '' }}">
            {!! Form::label('private_key', Lang::get('settings/model.private_key'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('private_key', Registry::get('private_key'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('private_key', ':message') }}</span>
            </div>
        </div>
        <!-- ./ private_key -->

        <!-- public_key -->
        <div class="form-group {{ $errors->has('public_key') ? 'has-error' : '' }}">
            {!! Form::label('public_key', Lang::get('settings/model.public_key'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('public_key', Registry::get('public_key'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('public_key', ':message') }}</span>
            </div>
        </div>
        <!-- ./ public_key -->

        <!-- mixed_mode -->
        <div class="form-group {{ $errors->has('mixed_mode') ? 'has-error' : '' }}">
            {!! Form::label('mixed_mode', Lang::get('settings/model.mixed_mode'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::checkbox('mixed_mode', Registry::get('mixed_mode'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('mixed_mode', ':message') }}</span>
            </div>
        </div>
        <!-- ./ mixed_mode -->

        <!-- ssham_file -->
        <div class="form-group {{ $errors->has('ssham_file') ? 'has-error' : '' }}">
            {!! Form::label('ssham_file', Lang::get('settings/model.ssham_file'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('ssham_file', Registry::get('ssham_file'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('ssham_file', ':message') }}</span>
            </div>
        </div>
        <!-- ./ ssham_file -->

        <!-- non_ssham_file -->
        <div class="form-group {{ $errors->has('non_ssham_file') ? 'has-error' : '' }}">
            {!! Form::label('non_ssham_file', Lang::get('settings/model.non_ssham_file'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('non_ssham_file', Registry::get('non_ssham_file'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('non_ssham_file', ':message') }}</span>
            </div>
        </div>
        <!-- ./ non_ssham_file -->

        <!-- ldap_host -->
        <div class="form-group {{ $errors->has('ldap_host') ? 'has-error' : '' }}">
            {!! Form::label('ldap_host', Lang::get('settings/model.ldap_host'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('ldap_host', Registry::get('ldap_host'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('ldap_host', ':message') }}</span>
            </div>
        </div>
        <!-- ./ ldap_host -->

        <!-- ldap_dn -->
        <div class="form-group {{ $errors->has('ldap_dn') ? 'has-error' : '' }}">
            {!! Form::label('ldap_dn', Lang::get('settings/model.ldap_dn'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('ldap_dn', Registry::get('ldap_dn'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('ldap_dn', ':message') }}</span>
            </div>
        </div>
        <!-- ./ ldap_dn -->

        <!-- cmd_keygen -->
        <div class="form-group {{ $errors->has('cmd_keygen') ? 'has-error' : '' }}">
            {!! Form::label('cmd_keygen', Lang::get('settings/model.cmd_keygen'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('cmd_keygen', Registry::get('cmd_keygen'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('cmd_keygen', ':message') }}</span>
            </div>
        </div>
        <!-- ./ cmd_keygen -->

        <!-- cmd_remote_updater -->
        <div class="form-group {{ $errors->has('cmd_remote_updater') ? 'has-error' : '' }}">
            {!! Form::label('cmd_remote_updater', Lang::get('settings/model.cmd_remote_updater'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('cmd_remote_updater', Registry::get('cmd_remote_updater'), array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('cmd_remote_updater', ':message') }}</span>
            </div>
        </div>
        <!-- ./ cmd_remote_updater -->

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

