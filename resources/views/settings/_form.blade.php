{{-- Edit Settings Form --}}

<div class="row">
    <div class="col-xs-12">

        <div class="tabbable">
            <ul id="myTab" class="nav nav-tabs tab-bricky">
                <li class="active">
                    <a href="#panel_settings_tab1" data-toggle="tab">
                        <i class="clip-keyhole"></i> SSH settings
                    </a>
                </li>
                <li>
                    <a href="#panel_settings_tab2" data-toggle="tab">
                        <i class="clip-key"></i> LDAP settings
                    </a>
                </li>
                <li>
                    <a href="#panel_settings_tab3" data-toggle="tab">
                        <i class="clip-wrench"></i> Advanced settings
                    </a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane in active" id="panel_settings_tab1">

                    <!-- private_key -->
                    <div class="form-group {{ $errors->has('private_key') ? 'has-error' : '' }}">
                        {!! Form::label('private_key', trans('settings/model.private_key'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::textarea('private_key', Registry::get('private_key'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('private_key', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ private_key -->

                    <!-- public_key -->
                    <div class="form-group {{ $errors->has('public_key') ? 'has-error' : '' }}">
                        {!! Form::label('public_key', trans('settings/model.public_key'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::textarea('public_key', Registry::get('public_key'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('public_key', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ public_key -->

                    <!-- SSH port -->
                    <div class="form-group {{ $errors->has('ssh_port') ? 'has-error' : '' }}">
                        {!! Form::label('ssh_port', trans('settings/model.ssh_port'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('ssh_port', Registry::get('ssh_port'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('ssh_port', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ SSH port -->

                    <!-- SSH connect timeout -->
                    <div class="form-group {{ $errors->has('ssh_timeout') ? 'has-error' : '' }}">
                        {!! Form::label('ssh_timeout', trans('settings/model.ssh_timeout'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::number('ssh_timeout', Registry::get('ssh_timeout'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('ssh_timeout', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ SSH connect timeout -->


                </div>
                <div class="tab-pane" id="panel_settings_tab2">

                    <!-- ldap_host -->
                    <div class="form-group {{ $errors->has('ldap_host') ? 'has-error' : '' }}">
                        {!! Form::label('ldap_host', trans('settings/model.ldap_host'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('ldap_host', Registry::get('ldap_host'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('ldap_host', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ ldap_host -->

                    <!-- ldap_dn -->
                    <div class="form-group {{ $errors->has('ldap_dn') ? 'has-error' : '' }}">
                        {!! Form::label('ldap_dn', trans('settings/model.ldap_dn'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('ldap_dn', Registry::get('ldap_dn'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('ldap_dn', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ ldap_dn -->

                </div>
                <div class="tab-pane" id="panel_settings_tab3">

                    <!-- mixed_mode -->
                    <div class="form-group {{ $errors->has('mixed_mode') ? 'has-error' : '' }}">
                        {!! Form::label('mixed_mode', trans('settings/model.mixed_mode'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::select('mixed_mode', array('1' => trans('general.yes'), '0' => trans('general.no')), Registry::get('mixed_mode'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('mixed_mode', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ mixed_mode -->

                    <!-- authorized_keys -->
                    <div class="form-group {{ $errors->has('authorized_keys') ? 'has-error' : '' }}">
                        {!! Form::label('authorized_keys', trans('settings/model.authorized_keys'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('authorized_keys', Registry::get('authorized_keys'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('authorized_keys', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ authorized_keys -->

                    <!-- ssham_file -->
                    <div class="form-group {{ $errors->has('ssham_file') ? 'has-error' : '' }}">
                        {!! Form::label('ssham_file', trans('settings/model.ssham_file'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('ssham_file', Registry::get('ssham_file'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('ssham_file', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ ssham_file -->

                    <!-- non_ssham_file -->
                    <div class="form-group {{ $errors->has('non_ssham_file') ? 'has-error' : '' }}">
                        {!! Form::label('non_ssham_file', trans('settings/model.non_ssham_file'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('non_ssham_file', Registry::get('non_ssham_file'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('non_ssham_file', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ non_ssham_file -->

                    <!-- cmd_remote_updater -->
                    <div class="form-group {{ $errors->has('cmd_remote_updater') ? 'has-error' : '' }}">
                        {!! Form::label('cmd_remote_updater', trans('settings/model.cmd_remote_updater'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('cmd_remote_updater', Registry::get('cmd_remote_updater'), array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('cmd_remote_updater', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ cmd_remote_updater -->
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12">

                <!-- Form Actions -->
                <div class="form-group">
                    <div class="controls">
                        {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                    </div>
                </div>
                <!-- ./ form actions -->

            </div>
        </div>
    </div>
</div>


