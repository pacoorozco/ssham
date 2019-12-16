{{-- Create / Edit User Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- username -->
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('username', trans('user/model.username'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($user))
                    {!! Form::text('username', null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                @else
                    {!! Form::text('username', null, array('class' => 'form-control')) !!}
                @endif
                <span class="help-block">{{ $errors->first('username', ':message') }}</span>
            </div>
        </div>
        <!-- ./ username -->

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

    </div>
    <div class="col-xs-6">

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

        @if (isset($user))
                <!-- administrator role -->
        <div class="form-group {{ $errors->has('is_admin') ? 'has-error' : '' }}">
            {!! Form::label('is_admin', trans('user/model.is_admin'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('is_admin', array('1' => trans('general.yes'), '0' => trans('general.no')), ($user->hasRole('admin') ? '1' : '0'), array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                <span class="help-block">{{ $errors->first('is_admin', ':message') }}</span>
            </div>
        </div>
        <!-- ./ administrator role -->
        @endif

        @if (isset($user))
                <!-- enabled -->
        <div class="form-group {{ $errors->has('enabled') ? 'has-error' : '' }}">
            {!! Form::label('enabled', trans('user/model.enabled'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('enabled', array('1' => trans('general.yes'), '0' => trans('general.no')),
                null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('enabled', ':message') }}</span>
            </div>
        </div>
        <!-- ./ enabled -->
        @endif

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

{{-- Styles --}}
@section('styles')
    {!! HTML::style(asset('plugins/select2/select2.css')) !!}
@endsection

{{-- Scripts --}}
@section('scripts')
    {!! HTML::script(asset('plugins/select2/select2.min.js')) !!}
    <script>
        $(".search-select").select2({
            placeholder: "@lang('user/messages.groups_help')",
            allowClear: true,
            language: "@lang('site.language_short')"
        });

        $("#create_rsa_key_false").click(function(){
            $("#create_rsa_key").hide();
            $("#public_key_input").removeAttr("disabled");
            $("#import_rsa_key").show();
        });

        $("#create_rsa_key_true").click(function(){
            $("#create_rsa_key").show();
            $("#public_key_input").attr("disabled", "disabled");
            $("#import_rsa_key").hide();
        });
    </script>
@endsection

