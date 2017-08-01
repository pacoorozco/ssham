{{-- Create / Edit user Form --}}
@if (isset($user))
    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'users.store']) !!}
@endif

<div class="box box-solid">
    <div class="box-body">

        <div class="callout callout-info">
            <p>@lang('general.mandatory_fields')</p>
        </div>
        <div class="row">
            <div class="col-sm-6">

                <fieldset>
                    <legend>@lang('user/title.details_field_set')</legend>

                    <!-- username -->
                    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        {!! Form::label('username', trans('user/model.username'), array('class' => 'control-label required')) !!}
                        <div class="controls">
                            @if (isset($user))
                                {!! Form::text('username', null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                            @else
                                {!! Form::text('username', null, array('class' => 'form-control', 'required' => 'required', 'placeholder' => trans('user/model.username_ph'))) !!}
                            @endif
                            <span class="help-block">{{ $errors->first('username', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ username -->

                    <!-- name -->
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        {!! Form::label('name', trans('user/model.name'), array('class' => 'control-label required')) !!}
                        <div class="controls">
                            {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'required', 'placeholder' => trans('user/model.name_ph'))) !!}
                            <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ name -->

                    <!-- email -->
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', trans('user/model.email'), array('class' => 'control-label required')) !!}
                        <div class="controls">
                            {!! Form::email('email', null, array('class' => 'form-control', 'required' => 'required', 'placeholder' => trans('user/model.email_ph'))) !!}
                            <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ email -->

                    <!-- password -->
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password', trans('user/model.password'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::password('password', array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ password -->

                    <!-- password_confirmation -->
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password_confirmation', trans('user/model.password_confirmation'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ password_confirmation -->

                </fieldset>

                @if (isset($user))
                    <fieldset>
                        <legend>@lang('user/title.roles_field_set')</legend>

                        <!-- role -->
                        <div class="form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                            {!! Form::label('role', trans('user/model.roles'), array('class' => 'control-label')) !!}
                            <div class="controls">
                                {!! Form::select('role', array('user' => trans('user/model.available_roles.user'), 'admin' => trans('user/model.available_roles.admin')), $user->role, array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('role', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ role -->

                        <!-- enabled -->
                        <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
                            {!! Form::label('active', trans('user/model.active'), array('class' => 'control-label')) !!}
                            <div class="controls">
                                {!! Form::select('active', array('1' => trans('general.yes'), '0' => trans('general.no')),
                                null, array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('active', ':message') }}</span>
                            </div>
                        </div>
                        <!-- ./ enabled -->
                    </fieldset>
                @endif

            </div>
            <div class="col-sm-6">

                <fieldset>
                    <legend>@lang('user/title.team_membership_field_set')</legend>


                    <!-- user's groups -->
                    <div class="form-group {{ $errors->has('groups[]') ? 'has-error' : '' }}">
                        {!! Form::label('groups[]', trans('user/model.groups'), array('class' => 'control-label')) !!}
                        <div class="controls">
                            @if (isset($user))
                                {!! Form::select('groups[]', $groups, $user->usergroups->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                            @else
                                {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                            @endif
                            <span class="help-block">{{ $errors->first('groups[]', ':message') }}</span>
                        </div>
                    </div>
                    <!-- ./ user's groups -->
                </fieldset>

                <fieldset>
                    <legend>{{ trans('user/title.ssh_field_set') }}</legend>

                    <!-- SSH public key -->
                    <div class="form-group {{ $errors->has('public_key') ? 'has-error' : '' }}">
                        {!! Form::label('public_key', trans('user/model.public_key'), array('class' => 'control-label')) !!}

                        <div class="controls">

                            <!-- create RSA key -->
                            @if (isset($user))
                                <label class="radio-inline">
                                    {!! Form::radio('create_rsa_key', '1', false, array('id' => 'create_rsa_key_true')) !!}
                                    @lang('user/messages.create_rsa_key')
                                </label>
                                <div id="create_rsa_key" style="display: none">
                                    <span class="help-block">@lang('user/messages.create_rsa_key_help')</span>
                                    <span class="help-block">@lang('user/messages.create_rsa_key_help_notice')</span>
                                </div>
                            @else
                                <label class="radio-inline">
                                    {!! Form::radio('create_rsa_key', '1', true, array('id' => 'create_rsa_key_true')) !!}
                                    @lang('user/messages.create_rsa_key')
                                </label>
                                <div id="create_rsa_key">
                                    <span class="help-block">@lang('user/messages.create_rsa_key_help')</span>
                                </div>
                            @endif
                            <!-- ./ create RSA key -->

                            <p></p>
                            <!-- import / edit public_key -->
                            @if (isset($user))
                                <label class="radio-inline">
                                    {!! Form::radio('create_rsa_key', '0', true, array('id' => 'create_rsa_key_false')) !!}
                                    @lang('user/messages.import_rsa_key')
                                </label>
                                <div id="import_rsa_key">
                                    <span class="help-block">@lang('user/messages.import_rsa_key_help')</span>
                                    {!! Form::textarea('public_key', null, array('class' => 'form-control', 'id' => 'public_key_input')) !!}
                                </div>
                            @else
                                <label class="radio-inline">
                                    {!! Form::radio('create_rsa_key', '0', false, array('id' => 'create_rsa_key_false')) !!}
                                    @lang('user/messages.import_rsa_key')
                                </label>
                                <div id="import_rsa_key" style="display: none">
                                    <span class="help-block">@lang('user/messages.import_rsa_key_help')</span>
                                    {!! Form::textarea('public_key', null, array('class' => 'form-control', 'id' => 'public_key_input', 'disabled' => 'disabled')) !!}
                                </div>
                            @endif

                            <span class="help-block">{{ $errors->first('public_key', ':message') }}</span>
                            <!-- ./ import / edit public_key -->
                        </div>
                    </div>
                    <!-- ./ SSH public key -->
                </fieldset>

            </div>
        </div>

    </div>
    <div class="box-footer">
        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                <a href="{{ route('users.index') }}" class="btn btn-link">@lang('general.cancel')</a>
            </div>
        </div>
        <!-- ./ form actions -->

    </div>
</div>
{!! Form::close() !!}

{{-- Styles --}}
@push('styles')
{!! HTML::style('vendor/AdminLTE/plugins/select2/select2.min.css') !!}
@endpush

{{-- Scripts --}}
@push('scripts')
{!! HTML::script('vendor/AdminLTE/plugins/select2/select2.min.js') !!}
<script>
    $(".search-select").select2({
        placeholder: "@lang('user/messages.groups_help')",
        allowClear: true,
        language: "@lang('site.language_short')"
    });

    $("#create_rsa_key_false").click(function () {
        $("#create_rsa_key").hide();
        $("#public_key_input").removeAttr("disabled");
        $("#import_rsa_key").show();
    });

    $("#create_rsa_key_true").click(function () {
        $("#create_rsa_key").show();
        $("#public_key_input").attr("disabled", "disabled");
        $("#import_rsa_key").hide();
    });
</script>
@endpush

